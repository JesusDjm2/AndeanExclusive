{{-- Agentes y viajeros: tarjetas verticales (diseño original). $locale: es|en --}}
@php
    $isEn = ($locale ?? 'es') === 'en';
@endphp

@if ($programa->agentes && $programa->agentes->count() > 0)
    <div class="program-section">
        <div class="section-header">
            <i class="fa fa-group"></i>
            <h3>{{ $isEn ? 'Assigned agents' : 'Agentes asignados' }}</h3>
        </div>

        <div class="participants-vertical-grid">
            @foreach ($programa->agentes as $agente)
                <div class="participant-vcard">
                    <div class="vcard-row">
                        <div class="vcard-icon">
                            @if ($agente->foto)
                                <img src="{{ asset($agente->foto) }}" alt="{{ $agente->nombre }}"
                                    style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            @else
                                <i class="fa fa-user-circle" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div class="vcard-name">{{ $agente->nombre }}</div>
                    </div>

                    <div class="vcard-details">
                        @if ($agente->telefono)
                            <div class="vcard-detail">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>{{ $agente->telefono }}</span>
                            </div>
                        @endif

                        @if ($agente->email)
                            <div class="vcard-detail">
                                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                <span>{{ $agente->email }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if ($programa->paxs->count() > 0)
    <div class="program-section">
        <div class="section-header">
            <i class="fa fa-users"></i>
            <h3>{{ $isEn ? 'Travelers' : 'Viajeros' }}</h3>
        </div>

        <div class="participants-vertical-grid">
            @foreach ($programa->paxs as $pax)
                <div class="participant-vcard">
                    <div class="vcard-row">
                        <div class="vcard-icon">
                            @if ($pax->edad < 5)
                                <i class="fa fa-baby" aria-hidden="true"></i>
                            @elseif($pax->edad < 12)
                                <i class="fa fa-child" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-user" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div class="vcard-name">{{ $pax->nombre }}</div>
                    </div>

                    <div class="vcard-details">
                        <div class="vcard-detail">
                            <i class="fa fa-birthday-cake" aria-hidden="true"></i>
                            <span>
                                {{ $pax->edad }}
                                {{ $isEn ? ($pax->edad == 1 ? 'year' : 'years') : 'años' }}
                            </span>
                        </div>

                        <div class="vcard-detail">
                            <i class="fa fa-flag" aria-hidden="true"></i>
                            <span>{{ $pax->nacionalidad }}</span>
                        </div>

                        @if ($pax->alimentacion)
                            <div class="vcard-detail">
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <span>{{ $pax->alimentacion }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
