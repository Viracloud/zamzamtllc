<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Defaults match the gold and black of the brand logo.
            $table->string('color_primary', 7)->default('#D4A24C')->after('about_image');
            $table->string('color_dark', 7)->default('#0E0E10')->after('color_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['color_primary', 'color_dark']);
        });
    }
};
