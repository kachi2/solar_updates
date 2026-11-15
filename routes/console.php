<?php

use App\Models\BlockedVisitor;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * The database cache (CACHE_STORE=database) never deletes expired rows by itself.
 * The rate limiter writes two rows per visitor, so bot traffic filled the table
 * (444k rows) and slowed every page. Expired rows are deleted every hour.
 * Needs the cPanel cron: * * * * * php /path/to/artisan schedule:run
 */
Artisan::command('cache:prune-expired', function () {
    $now = now()->getTimestamp();
    $deleted = DB::table('cache')->where('expiration', '<=', $now)->delete();
    DB::table('cache_locks')->where('expiration', '<=', $now)->delete();

    $this->info("Deleted {$deleted} expired cache rows.");
})->purpose('Delete expired rows from the database cache (rate-limit counters)');

Schedule::command('cache:prune-expired')->hourly()->withoutOverlapping();

/*
 * Block list (see BlockedVisitor). Unblocking resets the count, so the visitor
 * must hit the page limit `sofar.blocklist.after_recordings` times again.
 */
Artisan::command('visitors:unblock {ip}', function (string $ip) {
    $visitors = BlockedVisitor::where('ip', $ip)->get();

    if ($visitors->isEmpty()) {
        $this->warn("No blocked visitors with IP {$ip}.");

        return;
    }

    foreach ($visitors as $visitor) {
        BlockedVisitor::unblock($visitor->ip, $visitor->user_agent_hash);
        $visitor->update(['times_blocked' => 0]);
    }

    $this->info("Unblocked {$visitors->count()} visitor(s) with IP {$ip}.");
})->purpose('Remove an IP (all its browsers) from the block list');

// Re-creates the file-cache block keys from the table, e.g. after `cache:clear file`.
Artisan::command('visitors:sync-blocklist', function () {
    $count = 0;

    BlockedVisitor::where('times_blocked', '>=', BlockedVisitor::blockAfter())
        ->select(['id', 'ip', 'user_agent_hash'])
        ->chunkById(500, function ($visitors) use (&$count) {
            foreach ($visitors as $visitor) {
                BlockedVisitor::block($visitor->ip, $visitor->user_agent_hash);
                $count++;
            }
        });

    $this->info("{$count} visitor(s) on the block list.");
})->purpose('Rebuild the block list from the blocked_visitors table');
