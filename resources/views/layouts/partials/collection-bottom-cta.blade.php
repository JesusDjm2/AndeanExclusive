{{--
    Sección de conversión y llamado a la acción al pie de los listados de tours.
    Invita al usuario a cotizar un viaje a medida o a chatear en directo con un especialista.
    @param string $locale 'es'|'en'
    @param string|null $categoryName Nombre contextual de la colección (opcional).
--}}
@php
    $isEs = ($locale ?? 'es') === 'es';
    $cat = $categoryName ?? null;
    $waText = $isEs
        ? ($cat ? "¡Hola! Estuve viendo los itinerarios de {$cat} y quisiera diseñar un viaje a medida al Perú." : '¡Hola! Quisiera recibir asesoría para diseñar un viaje personalizado de lujo al Perú.')
        : ($cat ? "Hello! I was browsing your {$cat} itineraries and would love to design a bespoke trip to Peru." : 'Hello! I would like personal assistance to design a bespoke luxury journey to Peru.');
    $waUrl = 'https://wa.me/51979721194?text=' . rawurlencode($waText);
@endphp

<section class="collection-bottom-cta" aria-labelledby="collection-cta-title">
    <div class="site-container">
        <div class="collection-bottom-cta__card">
            <div class="collection-bottom-cta__content">
                <span class="collection-bottom-cta__eyebrow font-display">
                    {{ $isEs ? 'Tu viaje soñado, exactamente como lo imaginas' : 'Your dream journey, exactly as you envision' }}
                </span>
                <h2 class="collection-bottom-cta__title" id="collection-cta-title">
                    {{ $isEs ? '¿No encuentras el itinerario exacto? Lo diseñamos 100% a tu medida' : "Can't find the exact itinerary? We design it 100% tailor-made" }}
                </h2>
                <p class="collection-bottom-cta__lead">
                    {{ $isEs ? 'En Andean Exclusive no creemos en viajes estándar. Ajustamos cada día, hotel de lujo, experiencia gastronómica y ritmo de viaje según tus preferencias. Nuestro equipo local en Cusco te responde con una propuesta detallada en menos de 24 horas.' : 'At Andean Exclusive we do not believe in off-the-shelf packages. We handcraft every single day, luxury hotel, culinary highlight and walking pace around your desires. Our Cusco-based specialists reply with a bespoke proposal within 24 hours.' }}
                </p>

                <div class="collection-bottom-cta__actions">
                    <button type="button" class="btn-brand btn-brand--primary btn-brand--lg" data-contact-open>
                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                        <span>{{ $isEs ? 'Diseñar mi viaje a medida' : 'Design my tailor-made trip' }}</span>
                    </button>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn-brand btn-brand--outline btn-brand--lg">
                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                        <span>{{ $isEs ? 'Chatear con un especialista' : 'Chat with a travel specialist' }}</span>
                    </a>
                </div>

                <div class="collection-bottom-cta__trust">
                    <span class="collection-bottom-cta__trust-item">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        {{ $isEs ? 'Sin costo ni compromiso inicial' : 'No initial fee or obligation' }}
                    </span>
                    <span class="collection-bottom-cta__trust-item">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        {{ $isEs ? 'Propuesta en menos de 24h' : 'Proposal within 24 hours' }}
                    </span>
                    <span class="collection-bottom-cta__trust-item">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        {{ $isEs ? 'Operador boutique local en Cusco' : 'Direct local operator in Cusco' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
