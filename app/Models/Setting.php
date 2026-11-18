<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];

    /** Editable text fields, grouped as they appear in Admin > Company Settings. */
    public const TEXT_FIELDS = [
        'Company & Contact' => [
            'company_name'   => 'Company name',
            'base_location'  => 'Based in (City, State)',
            'phone'          => 'Dispatch phone',
            'email'          => 'Dispatch email (quote & application notifications are sent here)',
            'address_street' => 'Office street address',
            'address_city'   => 'Office City, State ZIP',
            'since_year'     => 'In business since (year)',
        ],
        'Authority & Insurance' => [
            'usdot_number'    => 'USDOT number',
            'mc_number'       => 'MC number (digits only)',
            'auto_liability'  => 'Auto liability coverage',
            'cargo_insurance' => 'Cargo insurance coverage',
            'fmcsa_url'       => 'FMCSA SAFER profile URL (leave empty to build it from the USDOT number)',
        ],
        'Fleet & Stats' => [
            'fleet_size'     => 'Trucks in fleet',
            'on_time_rate'   => 'On-time delivery rate',
            'max_payload'    => 'Trailer max payload',
            'tractor_models' => 'Tractor models',
            'model_years'    => 'Tractor model years',
            'eld_provider'   => 'ELD provider',
        ],
        'Careers' => [
            'driver_pay'                => 'Company driver pay per mile',
            'driver_home_time'          => 'Company driver home time',
            'owner_operator_percentage' => 'Owner-operator % of line haul',
            'min_experience'            => 'Minimum experience (years)',
        ],
        'Social Media' => [
            'social_facebook'  => 'Facebook page URL',
            'social_instagram' => 'Instagram profile URL',
            'social_tiktok'    => 'TikTok profile URL',
            'social_whatsapp'  => 'WhatsApp number (with country code, e.g. +1 214 555 0142)',
            'social_x'         => 'X (Twitter) profile URL',
            'social_truth'     => 'Truth Social profile URL',
        ],
    ];

    /** Fields that hold a full link (validated as URLs in the admin form). */
    public const URL_FIELDS = ['fmcsa_url', 'social_facebook', 'social_instagram', 'social_tiktok', 'social_x', 'social_truth'];

    /** Footer social icons that have a link set: [label, icon name in front.partials.social-icon, url]. */
    public function socialLinks(): array
    {
        $whatsapp = preg_replace('/\D/', '', (string) $this->social_whatsapp);
        $links = [
            ['Facebook', 'facebook', $this->social_facebook],
            ['Instagram', 'instagram', $this->social_instagram],
            ['TikTok', 'tiktok', $this->social_tiktok],
            ['WhatsApp', 'whatsapp', $whatsapp !== '' ? 'https://wa.me/' . $whatsapp : null],
            ['X (Twitter)', 'x', $this->social_x],
            ['Truth Social', 'truth', $this->social_truth],
        ];
        return array_values(array_filter($links, fn ($l) => filled($l[2])));
    }

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    public function fmcsaLink(): ?string
    {
        if ($this->fmcsa_url) {
            return $this->fmcsa_url;
        }
        $dot = preg_replace('/\D/', '', (string) $this->usdot_number);
        if ($dot === '' || (int) $dot === 0) {
            return null;
        }
        return 'https://safer.fmcsa.dot.gov/query.asp?searchtype=ANY&query_type=queryCarrierSnapshot&query_param=USDOT&query_string=' . $dot;
    }

    public function phoneHref(): string
    {
        return 'tel:' . preg_replace('/[^\d+]/', '', (string) $this->phone);
    }

    /** True when the field still holds a [placeholder] from the design file. */
    public function isPlaceholder(string $field): bool
    {
        $value = trim((string) $this->{$field});
        return $value === '' || str_contains($value, '[');
    }

    /** Labels of required fields that still hold placeholders. */
    public function missingFields(): array
    {
        $required = ['phone', 'email', 'address_street', 'address_city', 'base_location', 'usdot_number', 'mc_number', 'driver_pay'];
        $labels = array_merge(...array_values(self::TEXT_FIELDS));
        return collect($required)
            ->filter(fn ($f) => $this->isPlaceholder($f))
            ->map(fn ($f) => $labels[$f])
            ->values()
            ->all();
    }

    public function notificationEmail(): ?string
    {
        $email = trim((string) $this->email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    public function imageUrl(string $field, string $fallback): string
    {
        $path = $this->{$field} ? 'uploads/' . $this->{$field} : $fallback;
        // Favicons stay PNG; other images use the lighter WebP copy when there is one.
        if ($field !== 'favicon' && ($light = \App\Support\ImageOptimizer::webpFor($path))) {
            $path = $light;
        }
        return asset($path);
    }
}
