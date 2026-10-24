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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Branding
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('about_image')->nullable();

            // Company & contact
            $table->string('company_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('base_location')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('since_year')->nullable();

            // Authority & insurance
            $table->string('usdot_number')->nullable();
            $table->string('mc_number')->nullable();
            $table->string('auto_liability')->nullable();
            $table->string('cargo_insurance')->nullable();
            $table->string('fmcsa_url')->nullable();

            // Fleet & stats
            $table->string('fleet_size')->nullable();
            $table->string('on_time_rate')->nullable();
            $table->string('max_payload')->nullable();
            $table->string('tractor_models')->nullable();
            $table->string('model_years')->nullable();
            $table->string('eld_provider')->nullable();

            // Careers
            $table->string('driver_pay')->nullable();
            $table->string('driver_home_time')->nullable();
            $table->string('owner_operator_percentage')->nullable();
            $table->string('min_experience')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
