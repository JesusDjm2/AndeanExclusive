<?php

namespace App\Support;

use Dompdf\Dompdf;
use Dompdf\Options;

/** PDF de documentos internos: sin recursos remotos, para que un texto cargado no pueda hacer pedidos desde el servidor. */
class Pdf
{
    public static function desdeHtml(string $html): string
    {
        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
