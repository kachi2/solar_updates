<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Products get a readable URL slug (/product/{slug}) instead of an encrypted id.
 * Existing products are backfilled from their names; duplicate names get -2, -3…
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        $used = [];

        DB::table('products')->orderBy('id')->select('id', 'name')->each(function ($product) use (&$used) {
            $base = Str::slug((string) $product->name) ?: 'product';
            $slug = $base;

            for ($i = 2; isset($used[$slug]); $i++) {
                $slug = $base.'-'.$i;
            }

            $used[$slug] = true;
            DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
