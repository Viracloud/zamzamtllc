<?php

use App\Models\Section;
use App\Models\Setting;

/*
|--------------------------------------------------------------------------
| CMS helpers used by the front-end views
|--------------------------------------------------------------------------
*/

if (!function_exists('cms_tokens')) {
    /** Placeholder => value map built from Company Settings. */
    function cms_tokens(): array
    {
        static $tokens = null;
        if ($tokens !== null) {
            return $tokens;
        }
        $s = Setting::current();
        $tokens = ['{year}' => date('Y'), '{phone_link}' => $s->phoneHref()];
        foreach (array_keys(array_merge(...array_values(Setting::TEXT_FIELDS))) as $field) {
            $tokens['{' . $field . '}'] = (string) $s->{$field};
        }
        return $tokens;
    }
}

if (!function_exists('cms_text')) {
    /** Replace {placeholders} with Company Settings values. Output still needs escaping ({{ }}). */
    function cms_text(?string $text): string
    {
        return strtr((string) $text, cms_tokens());
    }
}

if (!function_exists('cms_html')) {
    /** Placeholders inside admin-authored HTML (rich text); values are escaped. */
    function cms_html(?string $html): string
    {
        return strtr((string) $html, array_map('e', cms_tokens()));
    }
}

if (!function_exists('cms_url')) {
    /** Admin links: "/about" becomes a site URL; http(s), mailto:, tel: and # links are kept. */
    function cms_url(?string $link): string
    {
        $link = trim(cms_text($link));
        if ($link === '') {
            return '#';
        }
        if (preg_match('#^(https?:|mailto:|tel:|\#)#i', $link)) {
            return $link;
        }
        return url(ltrim($link, '/'));
    }
}

if (!function_exists('cms_url_is_current')) {
    function cms_url_is_current(?string $link): bool
    {
        $link = trim((string) $link);
        if ($link === '' || preg_match('#^(https?:|mailto:|tel:|\#)#i', $link)) {
            return false;
        }
        $path = trim($link, '/');
        return $path === '' ? request()->is('/') : (request()->is($path) || request()->is($path . '/*'));
    }
}

if (!function_exists('cms_image')) {
    /** Image values are either a bundled asset path ("assets/...") or a file in public/uploads. */
    /** Image URL; serves the lighter .webp copy when one exists ($webp = false for social sharing tags). */
    function cms_image(?string $value, ?string $fallback = null, bool $webp = true): ?string
    {
        $value = $value ?: $fallback;
        if (!$value) {
            return null;
        }
        $path = str_starts_with($value, 'assets/') ? $value : 'uploads/' . $value;
        if ($webp && ($light = \App\Support\ImageOptimizer::webpFor($path))) {
            $path = $light;
        }
        return asset($path);
    }
}

if (!function_exists('cms_lines')) {
    function cms_lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $text)), 'strlen'));
    }
}

if (!function_exists('cms_paragraphs')) {
    function cms_paragraphs(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/', (string) $text)), 'strlen'));
    }
}

if (!function_exists('cms_global')) {
    /** One of the global sections: header, footer, forms. */
    function cms_global(string $type): Section
    {
        static $cache = [];
        return $cache[$type] ??= (Section::with('items')->where('page', 'global')->where('type', $type)->first() ?? new Section(['type' => $type]));
    }
}

if (!function_exists('cms_hero_image')) {
    /** Home banner image (used as default banner and sharing image); looked up once per request. */
    function cms_hero_image(): ?string
    {
        static $image = false;
        if ($image === false) {
            $image = Section::where('page', 'home')->where('type', 'hero')->value('image');
        }
        return $image;
    }
}

if (!function_exists('cms_services')) {
    /** Visible services in order; looked up once per request (footer, cards and lists share it). */
    function cms_services()
    {
        static $services = null;
        return $services ??= \App\Models\Service::active()->get();
    }
}

if (!function_exists('cms_label')) {
    /** A small label from Forms & Labels, falling back to its built-in default. */
    function cms_label(string $key): string
    {
        $value = trim((string) cms_global('forms')->field($key));
        return cms_text($value !== '' ? $value : (\App\Support\SectionTypes::LABELS[$key][1] ?? ''));
    }
}

if (!function_exists('cms_options')) {
    /** Option list from the global "forms" section, e.g. cms_options('equipment'). */
    function cms_options(string $group): array
    {
        return cms_global('forms')->group($group)->pluck('title')->map(fn ($t) => cms_text($t))->all();
    }
}
