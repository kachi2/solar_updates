<?php

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
