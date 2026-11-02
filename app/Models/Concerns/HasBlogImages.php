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

    private function blogImageUrl(?string $path, string $folder): string
    {
        $path = ltrim((string) $path, '/');

        return asset(str_starts_with($path, 'img/') ? $path : $folder . $path);
    }
}
