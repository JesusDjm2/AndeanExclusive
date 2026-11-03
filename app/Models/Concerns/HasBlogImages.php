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

    private function blogImageUrl(?string $path, string $folder): string
    {
        $path = ltrim((string) $path, '/');

        return asset(str_starts_with($path, 'img/') ? $path : $folder . $path);
    }
}
