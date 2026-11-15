<?php

namespace App\Models;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * A visitor (IP + browser) that hit the page limit. Used to find bot IP ranges.
 *
 * Guests recorded `sofar.blocklist.after_recordings` times are blocked for good:
 * a key is written to the FILE cache and BlockListedVisitors checks that key on
 * every request. The check never touches MySQL (CACHE_STORE is the database).
 * Unblock: php artisan visitors:unblock {ip}. Restore keys from this table after
 * the file cache was wiped: php artisan visitors:sync-blocklist.
 */
class BlockedVisitor extends Model
{
    protected $fillable = [
        'ip',
        'ip_range',
        'user_agent_hash',
        'user_agent',
        'last_url',
        'times_blocked',
        'first_blocked_at',
        'last_blocked_at',
    ];

    protected function casts(): array
    {
        return [
            'first_blocked_at' => 'datetime',
            'last_blocked_at' => 'datetime',
        ];
    }

    /**
     * Record a blocked request. Written at most once per 5 minutes per visitor,
     * so a bot that keeps retrying does not turn into a database write per request.
     */
    public static function record(Request $request): void
    {
        $ip = (string) $request->ip();
        $userAgent = (string) $request->userAgent();
        $hash = sha1($userAgent);

        if (! Cache::add('blocked-visitor|'.$ip.'|'.$hash, true, now()->addMinutes(5))) {
            return;
        }

        $now = now();
        $visitor = static::firstOrNew(['ip' => $ip, 'user_agent_hash' => $hash]);
        $visitor->fill([
            'ip_range' => static::rangeOf($ip),
            'user_agent' => Str::limit($userAgent, 500, ''),
            'last_url' => Str::limit($request->fullUrl(), 1000, ''),
            'times_blocked' => $visitor->exists ? $visitor->times_blocked + 1 : 1,
            'first_blocked_at' => $visitor->first_blocked_at ?? $now,
            'last_blocked_at' => $now,
        ])->save();

        // Logged-in customers are never blocked; they are only logged here.
        if (! $request->user() && $visitor->times_blocked >= static::blockAfter()) {
            static::block($ip, $hash);
        }
    }

    /**
     * Whether this request's IP + browser is on the block list (one file-cache read).
     */
    public static function isBlocked(Request $request): bool
    {
        return static::blocklist()->has(
            static::blockKey((string) $request->ip(), sha1((string) $request->userAgent()))
        );
    }

    public static function block(string $ip, string $userAgentHash): void
    {
        static::blocklist()->forever(static::blockKey($ip, $userAgentHash), true);
    }

    public static function unblock(string $ip, string $userAgentHash): void
    {
        static::blocklist()->forget(static::blockKey($ip, $userAgentHash));
    }

    public static function blockAfter(): int
    {
        return max(1, (int) config('sofar.blocklist.after_recordings'));
    }

    private static function blockKey(string $ip, string $userAgentHash): string
    {
        return 'blocklist|'.$ip.'|'.$userAgentHash;
    }

    /**
     * The block list lives in the file cache, not the default (database) cache.
     */
    private static function blocklist(): Repository
    {
        return Cache::store('file');
    }

    /**
     * The /24 (IPv4) or /64 (IPv6) range an IP belongs to, e.g. 102.89.4.0/24.
     */
    public static function rangeOf(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);

            return "{$parts[0]}.{$parts[1]}.{$parts[2]}.0/24";
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $packed = inet_pton($ip);

            return inet_ntop(substr($packed, 0, 8).str_repeat("\0", 8)).'/64';
        }

        return $ip;
    }
}
