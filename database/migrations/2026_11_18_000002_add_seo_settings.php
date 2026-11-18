<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('google_site_verification')->nullable();
            $table->string('bing_site_verification')->nullable();
            $table->string('google_analytics_id', 40)->nullable();
            $table->string('og_image')->nullable();
        });

        Schema::table('site_pages', function (Blueprint $table) {
            $table->boolean('noindex')->default(false);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->boolean('noindex')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['google_site_verification', 'bing_site_verification', 'google_analytics_id', 'og_image']);
        });
        Schema::table('site_pages', fn (Blueprint $table) => $table->dropColumn('noindex'));
        Schema::table('services', fn (Blueprint $table) => $table->dropColumn('noindex'));
    }
};
