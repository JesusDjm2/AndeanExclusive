<?php
$path = 'c:/xampp/htdocs/AndeanExclusive/resources/views/programas/form.blade.php';
$content = file_get_contents($path);

// 1. Update itin-summary with buttons
$targetSummary = <<<HTML
                    <div class="itin-summary">
                        <div class="itin-summary__stats">
                            <span><strong>{{ \$operacion['servicios'] }}</strong> servicios</span>
                            <span class="itin-summary__ok"><i class="fas fa-check-circle" aria-hidden="true"></i> {{ \$operacion['confirmados'] }} confirmados</span>
                            @if (\$operacion['abiertos'])
                                <span class="itin-summary__warn"><i class="fas fa-hourglass-half" aria-hidden="true"></i> {{ \$operacion['abiertos'] }} por confirmar</span>
                            @endif
                        </div>
                        <p class="prog-input-hint mb-0">El cliente ve el título y el texto de cada día y los servicios marcados como visibles. Proveedor, estado, confirmación y notas son internos.</p>
                    </div>
HTML;

// In case the file has different encoding or characters in comment/text, let's locate <div class="itin-summary">
$posSummary = strpos($content, '<div class="itin-summary">');
if ($posSummary !== false) {
    $endSummary = strpos($content, '</div>', $posSummary);
    // Find the closing </div> of itin-summary (it has an inner <div> for stats)
    $innerDiv = strpos($content, '<div class="itin-summary__stats">', $posSummary);
    $innerDivClose = strpos($content, '</div>', $innerDiv);
    $actualSummaryClose = strpos($content, '</div>', $innerDivClose + 6);

    $oldSummaryBlock = substr($content, $posSummary, ($actualSummaryClose + 6) - $posSummary);

    $newSummaryBlock = <<<HTML
<div class="itin-summary d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <div class="itin-summary__stats">
                                <span><strong>{{ \$operacion['servicios'] }}</strong> servicios</span>
                                <span class="itin-summary__ok"><i class="fas fa-check-circle" aria-hidden="true"></i> {{ \$operacion['confirmados'] }} confirmados</span>
                                @if (\$operacion['abiertos'])
                                    <span class="itin-summary__warn"><i class="fas fa-hourglass-half" aria-hidden="true"></i> {{ \$operacion['abiertos'] }} por confirmar</span>
                                @endif
                            </div>
                            <p class="prog-input-hint mb-0">El cliente ve el título y el texto de cada día y los servicios marcados como visibles. Proveedor, estado, confirmación y notas son internos.</p>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                            <button type="button" class="btn btn-sm btn-outline-danger" data-itin-remove-day title="Quitar el último día del itinerario">
                                <i class="fas fa-calendar-minus me-1" aria-hidden="true"></i> Quitar último día
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" data-itin-add-day>
                                <i class="fas fa-calendar-plus me-1" aria-hidden="true"></i> Agregar día
                            </button>
                        </div>
                    </div>
HTML;

    $content = substr_replace($content, $newSummaryBlock, $posSummary, strlen($oldSummaryBlock));
    echo "Summary block updated.\n";
} else {
    echo "Could not find itin-summary!\n";
}

// 2. Wrap foreach ($itinerario['dias'] as $dia) with <div class="itin-days-list" data-itin-days-list>
$posForeach = strpos($content, "@foreach (\$itinerario['dias'] as \$dia)");
if ($posForeach !== false) {
    // Check if already wrapped
    if (!str_contains($content, 'data-itin-days-list')) {
        $content = substr_replace($content, "<div class=\"itin-days-list\" data-itin-days-list>\n                    @foreach (\$itinerario['dias'] as \$dia)", $posForeach, strlen("@foreach (\$itinerario['dias'] as \$dia)"));

        // Now find matching @endforeach for this loop
        $posEndForeach = strpos($content, "@endforeach", $posForeach + 50);
        $footerButtons = <<<HTML
@endforeach
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top gap-2" data-itin-footer-actions>
                        <span class="text-muted small"><i class="fas fa-info-circle me-1" aria-hidden="true"></i> Puedes agregar o quitar días del itinerario directamente sin salir de esta pestaña.</span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" data-itin-remove-day title="Quitar el último día del itinerario">
                                <i class="fas fa-calendar-minus me-1" aria-hidden="true"></i> Quitar último día
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" data-itin-add-day>
                                <i class="fas fa-calendar-plus me-1" aria-hidden="true"></i> Agregar día
                            </button>
                        </div>
                    </div>
HTML;
        $content = substr_replace($content, $footerButtons, $posEndForeach, strlen("@endforeach"));
        echo "Wrapped days list and added footer buttons.\n";
    }
}

// 3. Add data attributes to <article class="itin-day"> inside the loop
$content = preg_replace(
    '/<article class="itin-day">(\s*<header class="itin-day__header">\s*<span class="itin-day__num">D[^<]*\{\{ \$dia\[\'numero\'\] \}\}<\/span>\s*<span class="itin-day__date">\{\{ [^<]+ \}\}<\/span>)/u',
    '<article class="itin-day" data-itin-day-item data-fecha="{{ $dia[\'clave\'] }}" data-dia-num="{{ $dia[\'numero\'] }}">$1',
    $content
);
$content = str_replace('<span class="itin-day__num">Día {{ $dia[\'numero\'] }}</span>', '<span class="itin-day__num" data-itin-day-num-label>Día {{ $dia[\'numero\'] }}</span>', $content);
$content = preg_replace('/(<span class="itin-day__date">)(\{\{ ucfirst\(\$dia\[\'fecha\'\]->locale\(\'es\'\)->isoFormat\(\'dddd D \[de\] MMMM\'\)\) \}\}<\/span>)/u', '<span class="itin-day__date" data-itin-day-date-label>$2', $content);

// 4. Add <template data-itin-day-template> before <template data-svc-template>
if (!str_contains($content, 'data-itin-day-template')) {
    $dayTemplate = <<<HTML
                    <template data-itin-day-template>
                        <article class="itin-day" data-itin-day-item data-fecha="__FECHA__" data-dia-num="__NUM__">
                            <header class="itin-day__header">
                                <span class="itin-day__num" data-itin-day-num-label>Día __NUM__</span>
                                <span class="itin-day__date" data-itin-day-date-label>__FECHA_LABEL__</span>
                            </header>
                            <div class="itin-day__body">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-5">
                                        <label class="prog-label" for="dia-__FECHA__-titulo">Título del día</label>
                                        <input id="dia-__FECHA__-titulo" type="text" name="dias[__FECHA__][titulo]" maxlength="255"
                                            class="form-control form-control-sm" placeholder="Ej. Llegada a Cusco y city tour">
                                    </div>
                                    <div class="col-md-7">
                                        <label class="prog-label" for="dia-__FECHA__-desc">Descripción para el cliente</label>
                                        <textarea id="dia-__FECHA__-desc" name="dias[__FECHA__][descripcion]" rows="2" maxlength="3000"
                                            class="form-control form-control-sm" placeholder="Detalles de las actividades de este día..."></textarea>
                                    </div>
                                </div>

                                <div class="itin-day__services" data-svc-list></div>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-svc-add data-fecha="__FECHA__">
                                    <i class="fas fa-plus" aria-hidden="true"></i> Agregar servicio
                                </button>
                            </div>
                        </article>
                    </template>

                    <template data-svc-template>
HTML;
    $content = str_replace('<template data-svc-template>', $dayTemplate, $content);
    echo "Added day template.\n";
}

// 5. Add modal-nuevo-hotel include before closing </div> of container if not already included
if (!str_contains($content, 'modal-nuevo-hotel')) {
    $content = str_replace('</div>' . "\n" . '@endsection', "    @include('programas.partials.modal-nuevo-hotel')\n</div>\n@endsection", $content);
    echo "Included modal-nuevo-hotel.\n";
}

file_put_contents($path, $content);
echo "form.blade.php updated successfully.\n";
