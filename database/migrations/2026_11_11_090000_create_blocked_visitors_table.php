<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Visitors that hit the page limit (see AppServiceProvider::configureRateLimits).
 * One row per IP + browser; `ip_range` (/24 for IPv4, /64 for IPv6) groups them
 * so busy ranges can be found and blacklisted.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45)->index();
            $table->string('ip_range', 50)->index();
            $table->char('user_agent_hash', 40);
            $table->string('user_agent', 512)->nullable();
            $table->string('last_url', 1024)->nullable();
            $table->unsignedInteger('times_blocked')->default(1);
            $table->timestamp('first_blocked_at')->nullable();
            $table->timestamp('last_blocked_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['ip', 'user_agent_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_visitors');
    }
};
