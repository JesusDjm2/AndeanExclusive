{{-- Lista de huéspedes para los hoteles (OperacionDiariaController@rooming). Dompdf solo admite estilos dentro del documento. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Lista de huéspedes {{ $programa->codigo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9.5pt; color: #2D3748; }
        h1 { font-size: 15pt; color: #078f84; margin: 0 0 2px; }
        h2 { font-size: 11pt; margin: 14px 0 4px; }
        p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th { background: #e6f4f3; text-align: left; font-size: 8.5pt; padding: 4px 6px; }
        td { border-bottom: 1px solid #dfe7e7; padding: 4px 6px; vertical-align: top; }
        .hoja { page-break-after: always; }
        .hoja:last-child { page-break-after: auto; }
        .nota { color: #5b6b6b; font-size: 8.5pt; margin-top: 8px; }
    </style>
</head>
<body>
    @foreach ($estadias as $hotelId => $filas)
        <div class="hoja">
            <h1>Lista de huéspedes</h1>
            <p><strong>{{ $hoteles[$hotelId]->nombre ?? 'Hotel' }}</strong></p>
            <p>Andean Exclusive Tours · Programa {{ $programa->codigo }} · {{ $programa->cliente?->etiqueta ?? $programa->nombre }}</p>

            <h2>Habitaciones</h2>
            <table>
                <thead><tr><th>Habitación</th><th>Ingreso</th><th>Salida</th><th>Noches</th></tr></thead>
                <tbody>
                    @foreach ($filas as $estadia)
                        <tr>
                            <td>{{ $estadia->etiqueta }}</td>
                            <td>{{ \Carbon\Carbon::parse($estadia->fecha_ingreso)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($estadia->fecha_salida)->format('d/m/Y') }}</td>
                            <td>{{ $estadia->noches }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h2>Huéspedes ({{ $programa->paxs->count() }})</h2>
            <table>
                <thead><tr><th>#</th><th>Nombre</th><th>Edad</th><th>Nacionalidad</th><th>Pasaporte</th><th>Alimentación</th></tr></thead>
                <tbody>
                    @foreach ($programa->paxs as $pax)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pax->nombre }}</td>
                            <td>{{ $pax->edad }}</td>
                            <td>{{ $pax->nacionalidad }}</td>
                            <td>{{ $pasaportes ? $pax->pasaporte : $pax->pasaporte_oculto }}</td>
                            <td>{{ $pax->alimentacion ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="nota">Documento confidencial: contiene datos personales. Úsese solo para gestionar esta reserva.</p>
        </div>
    @endforeach
</body>
</html>
