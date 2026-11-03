<?php

namespace App\Models\Concerns;

/**
 * Public URLs for blog images. Enblog stores only the file name
 * ("foo.webp") while Esblog stores the full relative path
 * ("img/thumb/foo.webp"); views should always go through these helpers.
 */
trait HasBlogImages
{
    public function thumbUrl(): string
    {
        return $this->blogImageUrl($this->imgThumb, 'img/thumb/');
    }

    public function fullUrl(): string
    {
        return $this->blogImageUrl($this->imgFull, 'img/full/');
    }

    /**
     * Optimized image URL for mobile viewports (<= 768px), preventing
     * heavy 1920-2700px desktop hero downloads on handheld devices.
     */
    public function mobileHeroUrl(): string
    {
        return $this->thumbUrl() ?: $this->fullUrl();
    }

    /**
     * Estimates reading time dynamically based on word count.
     */
    public function readingTime(string $locale = 'en'): string
    {
        $wordCount = str_word_count(strip_tags((string) $this->descripcion));
        $minutes = max(1, (int) ceil($wordCount / 220));

        return $locale === 'es' ? "{$minutes} min de lectura" : "{$minutes} min read";
    }

    /**
     * Sanitizes, localizes and enhances blog post body HTML:
     * - Rewrites legacy absolute domain references to relative paths
     * - Trims malformed trailing whitespace in src attributes
     * - Ensures lazy loading and async decoding on all embedded images
     * - Fixes legacy typos to prevent 404s
     */
    public function renderedContent(): string
    {
        $content = (string) $this->descripcion;

        // Normalize legacy domain references to relative paths
        $content = str_replace(
            ['https://andeanexclusive.com/', 'https://www.andeanexclusive.com/', 'http://andeanexclusive.com/'],
            '/',
            $content
        );

        // Fix known extension typo for Inti Raymi in blog body
        $content = str_replace(
            '/img/blog/thumb/inti-raymi-andean-exclusive.jpg',
            '/img/blog/thumb/inti-raymi-andean-exclusive.webp',
            $content
        );

        // Normalize src attributes and add responsive lazy loading
        $content = preg_replace_callback('/<img([^>]+)src=[\'"]([^\'"]+)[\'"]([^>]*)>/i', function ($matches) {
            $before = $matches[1];
            $src = trim($matches[2]);
            $after = $matches[3];

            $attrs = $before . ' ' . $after;
            if (!str_contains($attrs, 'loading=')) {
                $attrs .= ' loading="lazy"';
            }
            if (!str_contains($attrs, 'decoding=')) {
                $attrs .= ' decoding="async"';
            }

            return '<img src="' . $src . '"' . $attrs . '>';
        }, $content);

        return $content;
    }

    private function blogImageUrl(?string $path, string $folder): string
    {
        $path = ltrim((string) $path, '/');

        return asset(str_starts_with($path, 'img/') ? $path : $folder . $path);
    }
}
