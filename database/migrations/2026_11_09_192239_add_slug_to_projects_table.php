<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Projects get a readable URL slug (/project/{slug}) instead of an encrypted id.
 * Existing projects are backfilled from their titles; duplicates get -2, -3…
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        $used = [];

        DB::table('projects')->orderBy('id')->select('id', 'title')->each(function ($project) use (&$used) {
            $base = Str::slug((string) $project->title) ?: 'project';
            $slug = $base;

            for ($i = 2; isset($used[$slug]); $i++) {
                $slug = $base.'-'.$i;
            }

            $used[$slug] = true;
            DB::table('projects')->where('id', $project->id)->update(['slug' => $slug]);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
