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
     *
     * Safe to run more than once: existing admins, settings and content are kept.
     */
    public function run(): void
    {
        $this->seedSettings();
        $this->call([AdminSeeder::class, ContentSeeder::class]);
    }

    private function seedSettings(): void
    {
        $now = now();

        // -------- settings (singleton id = 1) --------
        $settings = [
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

            'updated_at' => $now,
        ];

        // The app creates an empty row on boot, so the row may exist before seeding.
        $existing = DB::table('settings')->where('id', 1)->first();
        if (!$existing) {
            DB::table('settings')->insert($settings + ['id' => 1, 'created_at' => $now]);
        } elseif (blank($existing->company_name)) {
            DB::table('settings')->where('id', 1)->update($settings);
        }
    }
}
