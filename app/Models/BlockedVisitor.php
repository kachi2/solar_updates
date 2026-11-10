<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * A visitor (IP + browser) that hit the page limit. Used to find bot IP ranges.
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
