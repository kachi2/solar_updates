<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * News articles get a readable URL slug (/news/{slug}) instead of an encrypted id.
 * Existing news are backfilled from their titles; duplicates get -2, -3…
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        $used = [];

        DB::table('news')->orderBy('id')->select('id', 'title')->each(function ($article) use (&$used) {
            $base = Str::slug((string) $article->title) ?: 'article';
            $slug = $base;

            for ($i = 2; isset($used[$slug]); $i++) {
                $slug = $base.'-'.$i;
            }

            $used[$slug] = true;
            DB::table('news')->where('id', $article->id)->update(['slug' => $slug]);
        });

        Schema::table('news', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
