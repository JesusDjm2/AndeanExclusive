{{-- Acción de otra área o sin permiso. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Sin permiso | Andean Exclusive Tours</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 1.5rem; background: #f4f7f8; color: #1b2a33; font-family: system-ui, sans-serif; text-align: center; }
        h1 { margin: 0 0 .5rem; font-size: 1.75rem; }
        p { margin: 0 0 1.25rem; color: #51626d; max-width: 34rem; }
        a { display: inline-block; padding: .65rem 1.2rem; border-radius: .4rem; background: #078f84; color: #fff; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
    <main>
        <h1>No tienes permiso para esta acción</h1>
        <p>{{ $exception->getMessage() ?: 'Si crees que deberías tenerlo, habla con Administración.' }}</p>
        <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ url('/home') }}">Ir al Panel</a>
            <a href="{{ url('/logout') }}" style="background:#e05260;">Cerrar sesión</a>
        </div>
    </main>
</body>
</html>
