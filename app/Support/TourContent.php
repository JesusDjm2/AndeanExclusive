<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Turns the free-form CKEditor HTML stored on tours into the structure the
 * tour detail page renders (day-by-day timeline, includes / excludes columns).
 * Parsing happens server-side so the markup is final on first paint and fully
 * crawlable; when the HTML doesn't follow the expected pattern the original
 * content is returned untouched.
 */
class TourContent
{
    /** "Day 1", "DAY 01:", "Día 2", "Dia 3." ... */
    private const DAY_PATTERN = '/^\s*(day|d[ií]a)\s*0*(\d+)\b[\s.:\-–—]*/iu';

    /** "Not include", "Not included", "No incluye", "Excluye", "Excludes" */
    private const EXCLUDES_PATTERN = '/\b(not\s+includ|no\s+inclu|exclu)/iu';

    /**
     * @return array{intro: string, days: array<int, array{number: int, title: string, html: string}>}
     */
    public static function itinerary(?string $html): array
    {
        $html = self::clean($html);
        $nodes = self::topLevelNodes($html);
        $intro = '';
        $days = [];

        foreach ($nodes as $node) {
            $text = trim($node->textContent ?? '');

            if (self::isHeading($node) && preg_match(self::DAY_PATTERN, $text, $m)) {
                $number = (int) $m[2];
                $title = trim(preg_replace(self::DAY_PATTERN, '', $text, 1));
                $last = $days ? count($days) - 1 : null;

                // Same day split in AM / PM blocks: one timeline stop, one sub-heading per half.
                if ($last !== null && $days[$last]['number'] === $number) {
                    if (! str_contains($days[$last]['title'], ' · ')) {
                        $days[$last]['html'] = '<h3>' . e($days[$last]['title']) . '</h3>' . $days[$last]['html'];
                    }
                    $days[$last]['title'] .= ' · ' . $title;
                    $days[$last]['html'] .= '<h3>' . e($title) . '</h3>';
                } else {
                    $days[] = ['number' => $number, 'title' => $title, 'html' => ''];
                }

                continue;
            }

            $markup = self::outerHtml($node);
            if ($days) {
                $days[count($days) - 1]['html'] .= $markup;
            } else {
                $intro .= $markup;
            }
        }

        // A single "day" isn't a timeline; keep the editor's layout.
        if (count($days) < 2) {
            return ['intro' => (string) $html, 'days' => []];
        }

        return ['intro' => trim($intro), 'days' => $days];
    }

    /**
     * @return array{includes: string, excludes: ?string}
     */
    public static function includes(?string $html): array
    {
        $includes = '';
        $excludes = null;

        foreach (self::topLevelNodes(self::clean($html)) as $node) {
            if (self::isHeading($node) && preg_match(self::EXCLUDES_PATTERN, $node->textContent ?? '')) {
                $excludes = '';

                continue;
            }

            $markup = self::outerHtml($node);
            if ($excludes === null) {
                $includes .= $markup;
            } else {
                $excludes .= $markup;
            }
        }

        return ['includes' => trim($includes), 'excludes' => $excludes !== null ? trim($excludes) : null];
    }

    /**
     * Drop inline style="" attributes the rich-text editor leaves behind, so
     * stored content follows the site stylesheet (images are made fluid there).
     */
    public static function clean(?string $html): string
    {
        return preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/i', '', (string) $html);
    }

    /**
     * Lazy-load embedded maps/videos (they are below the fold) and give them an
     * accessible title. Width/height stay as authored; CSS makes them fluid.
     */
    public static function lazyEmbeds(?string $html, string $title): string
    {
        return preg_replace_callback('/<iframe\b(?![^>]*\bloading=)([^>]*)>/i', function ($m) use ($title) {
            $attrs = $m[1];
            if (! preg_match('/\btitle=/i', $attrs)) {
                $attrs .= ' title="' . e($title) . '"';
            }

            return '<iframe loading="lazy"' . $attrs . '>';
        }, (string) $html);
    }

    /**
     * @return array<int, DOMNode>
     */
    private static function topLevelNodes(?string $html): array
    {
        if (trim((string) $html) === '') {
            return [];
        }

        $doc = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="tour-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('tour-root');
        if (! $root) {
            return [];
        }

        $nodes = [];
        foreach ($root->childNodes as $child) {
            // Skip whitespace-only text between blocks.
            if ($child->nodeType === XML_TEXT_NODE && trim($child->textContent) === '') {
                continue;
            }
            $nodes[] = $child;
        }

        return $nodes;
    }

    private static function isHeading(DOMNode $node): bool
    {
        return $node instanceof DOMElement && preg_match('/^h[1-6]$/i', $node->tagName);
    }

    private static function outerHtml(DOMNode $node): string
    {
        return $node->ownerDocument->saveHTML($node);
    }
}
