@php
    // $crumbs: [['label' => '...', 'url' => '...'], ..., ['label' => 'Current']]
    // Also emits schema.org BreadcrumbList JSON-LD into the layout <head>.
    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($crumbs)->values()->map(fn ($crumb, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $crumb['label'],
            'item' => $crumb['url'] ?? url()->current(),
        ])->all(),
    ];
@endphp
@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
<nav class="breadcrumb-trail" aria-label="{{ $ariaLabel ?? 'Breadcrumb' }}">
    @foreach ($crumbs as $crumb)
        @if ($loop->last || empty($crumb['url']))
            <span class="breadcrumb-trail__current" aria-current="page">{{ $crumb['label'] }}</span>
        @else
            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
            <span class="breadcrumb-trail__sep" aria-hidden="true">/</span>
        @endif
    @endforeach
</nav>
