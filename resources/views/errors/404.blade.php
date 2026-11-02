{{-- Página no encontrada: orienta al visitante y no se indexa. --}}
@php $es = app()->getLocale() === 'es' || str_starts_with((string) request()->header('Accept-Language'), 'es'); @endphp
<!DOCTYPE html>
<html lang="{{ $es ? 'es' : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $es ? 'Página no encontrada' : 'Page not found' }} | Andean Exclusive Tours</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 1.5rem; background: #f4f7f8; color: #1b2a33; font-family: system-ui, sans-serif; text-align: center; }
        h1 { margin: 0 0 .5rem; font-size: 1.75rem; }
        p { margin: 0 0 1.25rem; color: #51626d; }
        a { display: inline-block; padding: .65rem 1.2rem; border-radius: .4rem; background: #078f84; color: #fff; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
    <main>
        <h1>{{ $es ? 'No encontramos esta página' : 'We could not find this page' }}</h1>
        <p>{{ $es ? 'Puede que haya cambiado de dirección. Vuelva al inicio para seguir explorando Perú.' : 'It may have moved. Go back home to keep exploring Peru.' }}</p>
        <a href="{{ $es ? route('inicio') : route('index') }}">{{ $es ? 'Ir al inicio' : 'Go to homepage' }}</a>
    </main>
</body>
</html>
