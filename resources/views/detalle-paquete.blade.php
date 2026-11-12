<x-layout>
    <x-slot:titulo>World Travel | {{ $paquete->titulo }}</x-slot:titulo>
    <x-slot:recursos>
        @vite(['resources/css/catalogo-viajes.css'])
    </x-slot:recursos>

    <div class="wt-catalog wt-detail">
        <div class="wt-container wt-detail__back"><a href="{{ route('paquetes') }}">← Volver a paquetes</a><span> / {{ $paquete->destino }}</span></div>
        <section class="wt-container wt-detail__grid">
            <div>
                <div class="wt-detail__photo">
                    @if ($paquete->imagen_url)<img src="{{ $paquete->imagen_url }}" alt="Vista ilustrativa de {{ $paquete->destino }}">@endif
                    <span class="wt-detail__photo-label">WORLD TRAVEL · {{ strtoupper($paquete->pais) }}</span>
                </div>
                <div class="wt-detail__body">
                    <div class="wt-kicker">UNA EXPERIENCIA PARA RECORDAR</div>
                    <h1>{{ $paquete->titulo }}</h1>
                    <p class="wt-detail__location">{{ $paquete->destino }}, {{ $paquete->pais }} · {{ $paquete->duracion_dias }} días / {{ max(0, $paquete->duracion_dias - 1) }} noches</p>
                    <h2>Sobre este viaje</h2>
                    <p>{{ $paquete->descripcion }}</p>
                    <h2>¿Qué incluye?</h2>
                    <div class="wt-included">
                        <div><span aria-hidden="true">{{ $paquete->incluye_hotel ? '✓' : '—' }}</span> Alojamiento {{ $paquete->incluye_hotel ? 'incluido' : 'no incluido' }}</div>
                        <div><span aria-hidden="true">{{ $paquete->incluye_vuelo ? '✓' : '—' }}</span> Vuelo {{ $paquete->incluye_vuelo ? 'incluido' : 'no incluido' }}</div>
                        <div><span aria-hidden="true">{{ $paquete->incluye_tour ? '✓' : '—' }}</span> Actividades {{ $paquete->incluye_tour ? 'incluidas' : 'no incluidas' }}</div>
                    </div>
                    <div class="wt-detail__note"><strong>Importante:</strong> estos precios están expresados en USD y son demostrativos. El itinerario, los cupos y las reservas reales se incorporarán cuando el equipo implemente su Backend correspondiente.</div>
                </div>
            </div>

            <aside class="wt-booking-card">
                <div class="wt-booking-card__eyebrow">TU PRÓXIMA EXPERIENCIA</div>
                @if ($oferta)
                    @php($final = round((float) $paquete->precio * (1 - $oferta->descuento_porcentaje / 100), 2))
                    <span class="wt-booking-card__discount">Promoción -{{ $oferta->descuento_porcentaje }}%</span>
                    <p class="wt-booking-card__old">Precio habitual: <s>US$ {{ number_format((float) $paquete->precio, 2, '.', ',') }}</s></p>
                    <div class="wt-booking-card__price">US$ {{ number_format($final, 2, '.', ',') }}</div>
                    <small>Por persona · USD · precio promocional de prueba</small>
                    <p class="wt-booking-card__valid">Hasta el {{ $oferta->fecha_fin->format('d/m/Y') }}</p>
                @else
                    <div class="wt-booking-card__price">US$ {{ number_format((float) $paquete->precio, 2, '.', ',') }}</div>
                    <small>Desde / por persona · USD · precio de prueba</small>
                @endif
                <hr>
                <div class="wt-booking-card__line"><span>Destino</span><strong>{{ $paquete->destino }}</strong></div>
                <div class="wt-booking-card__line"><span>Duración</span><strong>{{ $paquete->duracion_dias }} días</strong></div>
                <div class="wt-booking-card__line"><span>Alojamiento</span><strong>{{ $paquete->incluye_hotel ? 'Incluido' : 'No incluido' }}</strong></div>
                <div class="wt-booking-card__line"><span>Vuelo</span><strong>{{ $paquete->incluye_vuelo ? 'Incluido' : 'No incluido' }}</strong></div>
                <div class="wt-booking-card__line"><span>Actividades</span><strong>{{ $paquete->incluye_tour ? 'Incluidas' : 'No incluidas' }}</strong></div>
                <button class="wt-button wt-button--blue wt-booking-card__disabled" type="button" disabled>Reservar próximamente</button>
                <p class="wt-booking-card__disclaimer">Todavía no se realizan reservas ni pagos desde esta sección.</p>
                <a class="wt-booking-card__link" href="{{ route('ofertas') }}">Descubrir otras ofertas →</a>
            </aside>
        </section>
    </div>
</x-layout>
