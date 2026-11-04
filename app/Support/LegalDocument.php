<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Adds anchors to the numbered <h3> clauses of a legal document and returns
 * the matching table of contents, so long pages (terms & conditions) can be
 * navigated by clause without hand-maintaining ids in the markup.
 */
class LegalDocument
{
    /** Kept capitalised when headings are converted to sentence case. */
    private const PROPER_NOUNS = ['Andean Exclusive Tours', 'Perú', 'Peru', 'God'];

    /**
     * @return array{html: string, toc: array<int, array{id: string, label: string}>}
     */
    public static function withToc(string $html): array
    {
        $toc = [];

        $html = preg_replace_callback('/<h3>(.*?)<\/h3>/s', function ($m) use (&$toc) {
            $label = trim(html_entity_decode(strip_tags($m[1])), " \t\n\r\0\x0B:");
            $id = Str::slug($label) ?: 'section-' . (count($toc) + 1);
            // "1. SERVICES" -> "1. Services": sentence case reads better in a sidebar list.
            $sentence = preg_replace_callback('/\p{L}/u', fn ($c) => mb_strtoupper($c[0]), mb_strtolower($label), 1);
            $sentence = str_ireplace(self::PROPER_NOUNS, self::PROPER_NOUNS, $sentence);
            $toc[] = ['id' => $id, 'label' => $sentence];

            return '<h3 id="' . e($id) . '" data-toc-section>' . $m[1] . '</h3>';
        }, $html);

        return ['html' => $html, 'toc' => $toc];
    }
}
