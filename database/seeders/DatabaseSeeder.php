<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Values in [brackets] are the placeholders from the design file; they are
     * meant to be replaced from Admin > Company Settings.
     */
    public function run(): void
    {
        $this->call([AdminSeeder::class, ContentSeeder::class]);

        $now = now();

        // -------- settings (singleton id = 1) --------
        DB::table('settings')->insert([
            'id' => 1,
            'logo' => null,
            'favicon' => null,
            'hero_image' => null,
            'about_image' => null,

            'company_name' => 'ZAM ZAM TRUCKING LLC',
            'tagline' => 'Your Trusted Transportation Partner Across the United States',
            'base_location' => '[City, State]',
            'phone' => '[(000) 000-0000]',
            'email' => '[dispatch@zamzamtrucking.com]',
            'address_street' => '[Street address]',
            'address_city' => '[City, State ZIP]',
            'since_year' => '2020',

            'usdot_number' => '[0000000]',
            'mc_number' => '[000000]',
            'auto_liability' => '$1,000,000',
            'cargo_insurance' => '$100,000',
            'fmcsa_url' => null,

            'fleet_size' => '4+',
            'on_time_rate' => '100%',
            'max_payload' => '45,000 lbs',
            'tractor_models' => 'Freightliner Cascadia',
            'model_years' => '2021+',
            'eld_provider' => 'Motive',

            'driver_pay' => '[$0.00]',
            'driver_home_time' => 'every weekend',
            'owner_operator_percentage' => '88%',
            'min_experience' => '1',

            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
