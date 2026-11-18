<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SitePage;
use App\Support\Theme;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    /** Uploadable images: field => [label, help text]. */
    public const IMAGES = [
        'logo' => ['Logo', 'Round or square logo shown in the header, footer and admin panel (displayed as a circle, next to the company name). PNG with a transparent background, at least 240×240.'],
        'favicon' => ['Favicon', 'Small square icon for the browser tab (PNG, 64×64 or larger). Leave empty to use the logo.'],
    ];

    public function company()
    {
        $setting = Setting::current();
        return view('admin.setting.company', ['setting' => $setting, 'groups' => Setting::TEXT_FIELDS]);
    }

    public function company_update(Request $request)
    {
        $fields = array_keys(array_merge(...array_values(Setting::TEXT_FIELDS)));
        $rules = array_fill_keys($fields, 'nullable|string|max:255');
        $rules['company_name'] = 'required|string|max:255';
        foreach (Setting::URL_FIELDS as $field) {
            $rules[$field] = 'nullable|url|max:255';
        }

        $data = $request->validate($rules);
        Setting::current()->update($data);

        return redirect()->back()->with('success', 'Company settings updated.');
    }

    public function theme()
    {
        $setting = Setting::current();
        return view('admin.setting.theme', [
            'setting' => $setting,
            'variables' => Theme::variables($setting->color_primary, $setting->color_dark),
        ]);
    }

    public function theme_update(Request $request)
    {
        $data = $request->validate([
            'color_primary' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_dark' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ], [
            'regex' => 'The :attribute must be a hex colour like #D4A24C.',
        ], [
            'color_primary' => 'accent colour',
            'color_dark' => 'dark colour',
        ]);
        Setting::current()->update(array_map('strtoupper', $data));

        return redirect()->back()->with('success', 'Theme colours updated.');
    }

    public function seo()
    {
        return view('admin.setting.seo', [
            'setting' => Setting::current(),
            'pages' => SitePage::whereNotNull('slug')->orWhere('key', 'home')->orderBy('sort_order')->get(),
            'services' => Service::orderBy('sort_order')->get(),
        ]);
    }

    public function seo_update(Request $request)
    {
        // People often paste the whole <meta> tag; keep only the code inside content="…".
        foreach (['google_site_verification', 'bing_site_verification'] as $field) {
            $value = trim((string) $request->input($field));
            if (preg_match('/content=["\']([^"\']+)["\']/i', $value, $m)) {
                $value = $m[1];
            }
            $request->merge([$field => $value]);
        }

        $data = $request->validate([
            'google_site_verification' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'bing_site_verification' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'google_analytics_id' => ['nullable', 'string', 'max:40', 'regex:/^G-[A-Z0-9]{4,}$/i'],
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'google_analytics_id.regex' => 'The Measurement ID looks like G-XXXXXXXXXX.',
            'regex' => 'Paste only the verification code (letters, numbers, - and _).',
        ], [
            'google_site_verification' => 'Google verification code',
            'bing_site_verification' => 'Bing verification code',
            'google_analytics_id' => 'Google Analytics ID',
            'og_image' => 'sharing image',
        ]);

        $setting = Setting::current();
        $setting->fill([
            'google_site_verification' => $data['google_site_verification'] ?: null,
            'bing_site_verification' => $data['bing_site_verification'] ?: null,
            'google_analytics_id' => $data['google_analytics_id'] ? strtoupper($data['google_analytics_id']) : null,
        ]);
        if ($request->hasFile('og_image')) {
            $this->deleteUpload($setting->og_image);
            $name = 'og_image_' . time() . '.' . $request->file('og_image')->extension();
            $request->file('og_image')->move(public_path('uploads'), $name);
            $setting->og_image = $name;
        }
        $setting->save();

        return redirect()->back()->with('success', 'SEO settings saved.');
    }

    public function images()
    {
        return view('admin.setting.images', ['setting' => Setting::current(), 'images' => self::IMAGES]);
    }

    public function images_update(Request $request)
    {
        $rules = [];
        foreach (array_keys(self::IMAGES) as $field) {
            $rules[$field] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120';
        }
        $request->validate($rules);

        $setting = Setting::current();
        foreach (array_keys(self::IMAGES) as $field) {
            if (!$request->hasFile($field)) {
                continue;
            }
            $this->deleteUpload($setting->{$field});
            $name = $field . '_' . time() . '.' . $request->file($field)->extension();
            $request->file($field)->move(public_path('uploads'), $name);
            if ($field !== 'favicon') {
                \App\Support\ImageOptimizer::makeWebp(public_path('uploads/' . $name));
            }
            $setting->{$field} = $name;
        }
        $setting->save();

        return redirect()->back()->with('success', 'Images updated.');
    }

    public function image_reset(string $field)
    {
        $labels = array_map(fn ($i) => $i[0], self::IMAGES) + ['og_image' => 'Sharing image'];
        abort_unless(array_key_exists($field, $labels), 404);
        $setting = Setting::current();
        $this->deleteUpload($setting->{$field});
        $setting->update([$field => null]);

        return redirect()->back()->with('success', $labels[$field] . ' reset to default.');
    }

    private function deleteUpload(?string $file): void
    {
        if ($file && file_exists(public_path('uploads/' . $file))) {
            unlink(public_path('uploads/' . $file));
        }
        if ($file && ($webp = \App\Support\ImageOptimizer::webpFor('uploads/' . $file))) {
            unlink(public_path($webp));
        }
    }
}
