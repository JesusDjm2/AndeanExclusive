<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

/**
 * Limpia el HTML del editor antes de mostrárselo a un cliente: conserva el formato y quita lo que puede ejecutar código.
 */
class HtmlSeguro
{
    private const ETIQUETAS = ['p', 'br', 'hr', 'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup', 'span', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'blockquote', 'a', 'img', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'caption', 'pre', 'code'];

    private const ATRIBUTOS = ['href', 'src', 'alt', 'title', 'target', 'rel', 'width', 'height', 'colspan', 'rowspan', 'style', 'class'];

    public static function limpiar(?string $html): ?string
    {
        if (blank($html)) {
            return $html;
        }

        libxml_use_internal_errors(true);
        $documento = new DOMDocument;
        $documento->loadHTML('<?xml encoding="utf-8" ?><div id="raiz">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        foreach ((new DOMXPath($documento))->query('//*[@id="raiz"]//*') as $elemento) {
            /** @var DOMElement $elemento */
            if (! $elemento->parentNode) {
                continue;
            }
            if (! in_array(strtolower($elemento->tagName), self::ETIQUETAS, true)) {
                self::quitar($elemento);

                continue;
            }
            foreach (iterator_to_array($elemento->attributes) as $atributo) {
                $nombre = strtolower($atributo->name);
                $valor = preg_replace('/[\x00-\x20]+/', '', html_entity_decode((string) $atributo->value));
                $enlacePeligroso = in_array($nombre, ['href', 'src'], true) && preg_match('/^(javascript|vbscript|data:text)/i', $valor);
                $estiloPeligroso = $nombre === 'style' && preg_match('/expression\(|url\(|@import/i', $valor);
                if (! in_array($nombre, self::ATRIBUTOS, true) || $enlacePeligroso || $estiloPeligroso) {
                    $elemento->removeAttribute($atributo->name);
                }
            }
        }

        $salida = '';
        foreach ($documento->getElementById('raiz')?->childNodes ?? [] as $hijo) {
            $salida .= $documento->saveHTML($hijo);
        }

        return $salida;
    }

    /** Scripts y estilos se van con su contenido; el resto conserva su texto. */
    private static function quitar(DOMNode $elemento): void
    {
        if (! in_array(strtolower($elemento->nodeName), ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'link', 'meta', 'svg', 'math'], true)) {
            while ($elemento->firstChild) {
                $elemento->parentNode->insertBefore($elemento->firstChild, $elemento);
            }
        }
        $elemento->parentNode->removeChild($elemento);
    }
}
