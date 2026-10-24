<?php

namespace App\Support;

/**
 * Builds the site's CSS colour variables from two base colours chosen in the
 * admin panel (primary accent + dark base). Every other shade is derived, and
 * text colours are picked to keep WCAG AA contrast whatever colours are chosen.
 */
class Theme
{
    public const DEFAULT_PRIMARY = '#D4A24C';
    public const DEFAULT_DARK = '#0E0E10';

    private const WHITE = [255, 255, 255];
    private const BLACK = [0, 0, 0];

    public static function isHex(?string $value): bool
    {
        return is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value) === 1;
    }

    /** @return array<string, string> CSS custom property => value */
    public static function variables(?string $primary, ?string $dark): array
    {
        $accent = self::rgb(self::isHex($primary) ? $primary : self::DEFAULT_PRIMARY);
        $base = self::rgb(self::isHex($dark) ? $dark : self::DEFAULT_DARK);

        $dark950 = self::mix($base, self::BLACK, 0.35);

        return [
            '--accent' => self::hex($accent),
            '--accent-rgb' => implode(', ', $accent),
            '--accent-hover' => self::hex(self::mix($accent, self::WHITE, 0.18)),
            '--accent-light' => self::hex(self::mix($accent, self::WHITE, 0.4)),
            '--accent-deep' => self::hex(self::mix($accent, self::BLACK, 0.3)),
            '--accent-50' => self::hex(self::mix($accent, self::WHITE, 0.88)),
            // Accent used as text on white / light grey backgrounds
            '--accent-text' => self::hex(self::readableOn($accent, [245, 247, 251], self::BLACK)),
            // Accent used as text/icons on the dark sections
            '--accent-on-dark' => self::hex(self::readableOn($accent, $base, self::WHITE)),
            // Text placed on an accent-coloured background
            '--accent-ink' => self::hex(self::contrast($base, $accent) >= self::contrast(self::WHITE, $accent) ? $base : self::WHITE),

            '--dark-950' => self::hex($dark950),
            '--dark-900' => self::hex($base),
            '--dark-800' => self::hex(self::mix($base, self::WHITE, 0.06)),
            '--dark-700' => self::hex(self::mix($base, self::WHITE, 0.13)),
            '--dark-rgb' => implode(', ', array_map(fn ($c) => (int) round($c), $dark950)),
        ];
    }

    public static function css(?string $primary, ?string $dark): string
    {
        $lines = [];
        foreach (self::variables($primary, $dark) as $name => $value) {
            $lines[] = $name . ': ' . $value . ';';
        }
        return ':root { ' . implode(' ', $lines) . ' }';
    }

    /** Move $color toward $toward until it reaches 4.5:1 against $background. */
    private static function readableOn(array $color, array $background, array $toward): array
    {
        for ($t = 0.0; $t <= 1.0; $t += 0.04) {
            $candidate = self::mix($color, $toward, $t);
            if (self::contrast($candidate, $background) >= 4.5) {
                return $candidate;
            }
        }
        return $toward;
    }

    private static function rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }

    private static function hex(array $rgb): string
    {
        return sprintf('#%02X%02X%02X', ...array_map(fn ($c) => max(0, min(255, (int) round($c))), $rgb));
    }

    private static function mix(array $a, array $b, float $t): array
    {
        return array_map(fn ($x, $y) => $x + ($y - $x) * $t, $a, $b);
    }

    private static function luminance(array $rgb): float
    {
        [$r, $g, $b] = array_map(function ($c) {
            $c /= 255;
            return $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4;
        }, $rgb);
        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    private static function contrast(array $a, array $b): float
    {
        $la = self::luminance($a);
        $lb = self::luminance($b);
        return (max($la, $lb) + 0.05) / (min($la, $lb) + 0.05);
    }
}
