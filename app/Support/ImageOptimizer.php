<?php

namespace App\Support;

/**
 * Makes a smaller WebP copy next to a JPG/PNG (photo.jpg -> photo.webp).
 * The original stays; pages serve the WebP when it exists (see cms_image / Setting::imageUrl).
 */
class ImageOptimizer
{
    public const QUALITY = 80;
    public const MAX_WIDTH = 1920;

    /** Path of the WebP copy for a public-relative path, or null when there is none. */
    public static function webpFor(string $relative): ?string
    {
        if (!preg_match('/\.(jpe?g|png)$/i', $relative)) {
            return null;
        }
        $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $relative);
        return is_file(public_path($webp)) ? $webp : null;
    }

    /** Create the WebP copy for an absolute file path. Returns true when written. */
    public static function makeWebp(string $file): bool
    {
        if (!function_exists('imagewebp') || !is_file($file) || !preg_match('/\.(jpe?g|png)$/i', $file)) {
            return false;
        }
        try {
            $image = preg_match('/\.png$/i', $file) ? @imagecreatefrompng($file) : @imagecreatefromjpeg($file);
            if (!$image) {
                return false;
            }
            if (imagesx($image) > self::MAX_WIDTH) {
                $image = imagescale($image, self::MAX_WIDTH) ?: $image;
            }
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            $target = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);
            $ok = imagewebp($image, $target, self::QUALITY);
            unset($image);

            // Keep the WebP only when it is really smaller.
            if ($ok && filesize($target) >= filesize($file)) {
                unlink($target);
                return false;
            }
            return $ok;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
