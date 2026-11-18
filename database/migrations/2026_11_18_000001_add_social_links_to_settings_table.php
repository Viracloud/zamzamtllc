<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const COLUMNS = ['social_facebook', 'social_instagram', 'social_tiktok', 'social_whatsapp', 'social_x', 'social_truth'];

    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (self::COLUMNS as $column) {
                $table->string($column)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(self::COLUMNS);
        });
    }
};
