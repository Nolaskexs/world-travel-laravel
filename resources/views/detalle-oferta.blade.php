<x-layout>
    <x-slot:titulo>World Travel | {{ $oferta->titulo }}</x-slot:titulo>
    <x-slot:recursos>
        @vite(['resources/css/catalogo-viajes.css'])
    </x-slot:recursos>

    <div class="wt-catalog wt-detail">
        <div class="wt-container wt-detail__back">
            <a href="{{ route('ofertas') }}">← Volver a ofertas</a>
            <span> / {{ $paquete->destino }}</span>
        </div>

        <section class="wt-container wt-detail__grid">
            <div>
                <div class="wt-detail__photo">
                    @if ($paquete->imagen_url)
                        <img src="{{ $paquete->imagen_url }}" alt="Vista ilustrativa de {{ $paquete->destino }}">
                    @endif
                    <span class="wt-detail__photo-label">PROMOCIÓN · -{{ $oferta->descuento_porcentaje }}%</span>
                </div>

                <div class="wt-detail__body">
                    <div class="wt-kicker">OFERTA VIGENTE HASTA EL {{ $oferta->fecha_fin->format('d/m/Y') }}</div>
                    <h1>{{ $oferta->titulo }}</h1>
                    <p class="wt-detail__location">{{ $paquete->destino }}, {{ $paquete->pais }} · {{ $paquete->duracion_dias }} días / {{ max(0, $paquete->duracion_dias - 1) }} noches</p>

                    <h2>Sobre esta promoción</h2>
                    <p>{{ $paquete->descripcion }}</p>

                    <h2>Servicios incluidos</h2>
                    <div class="wt-included">
                        <div>Alojamiento: <strong>{{ $paquete->incluye_hotel ? 'Incluido' : 'No incluido' }}</strong></div>
                        <div>Vuelo: <strong>{{ $paquete->incluye_vuelo ? 'Incluido' : 'No incluido' }}</strong></div>
                        <div>Actividades: <strong>{{ $paquete->incluye_tour ? 'Incluidas' : 'No incluidas' }}</strong></div>
                    </div>

                    <div class="wt-detail__note">
                        <strong>Descuento aplicado:</strong> {{ $oferta->descuento_porcentaje }}% sobre el precio habitual del paquete.
                        Ahorras US$ {{ number_format($ahorro, 2, '.', ',') }} por persona.
                    </div>
                </div>
            </div>

            <aside class="wt-booking-card">
                <div class="wt-booking-card__eyebrow">TU PRÓXIMA EXPERIENCIA</div>
                <span class="wt-booking-card__discount">Promoción -{{ $oferta->descuento_porcentaje }}%</span>
                <p class="wt-booking-card__old">Precio habitual: <s>US$ {{ number_format($precioOriginal, 2, '.', ',') }}</s></p>
                <div class="wt-booking-card__price">US$ {{ number_format($precioFinal, 2, '.', ',') }}</div>
                <small style="display:block; font-size:12px; color:#64748b; font-weight:600; margin-top:-4px; margin-bottom:4px;">aprox. S/ {{ number_format($precioFinal * 3.51, 2, '.', ',') }}</small>
                <small>Por persona · USD · precio promocional</small>
                <p class="wt-booking-card__valid">Hasta el {{ $oferta->fecha_fin->format('d/m/Y') }}</p>

                <hr>

                <div class="wt-booking-card__line"><span>Paquete</span><strong>{{ $paquete->titulo }}</strong></div>
                <div class="wt-booking-card__line"><span>Destino</span><strong>{{ $paquete->destino }}</strong></div>
                <div class="wt-booking-card__line"><span>Duración</span><strong>{{ $paquete->duracion_dias }} días</strong></div>
                <div class="wt-booking-card__line"><span>Alojamiento</span><strong>{{ $paquete->incluye_hotel ? 'Incluido' : 'No incluido' }}</strong></div>
                <div class="wt-booking-card__line"><span>Vuelo</span><strong>{{ $paquete->incluye_vuelo ? 'Incluido' : 'No incluido' }}</strong></div>
                <div class="wt-booking-card__line"><span>Actividades</span><strong>{{ $paquete->incluye_tour ? 'Incluidas' : 'No incluidas' }}</strong></div>

                <a href="{{ route('reservas.confirmar.ofertas', ['oferta_id' => $oferta->id]) }}" class="wt-button wt-button--blue" style="display: block; text-align: center; text-decoration: none; font-weight: 700; padding: 14px;">Reservar esta oferta</a>
                @guest
                    <small style="display: block; text-align: center; color: #64748b; margin-top: 6px; font-size: 0.78rem;">Requiere iniciar sesión para reservar</small>
                @endguest
                <p class="wt-booking-card__disclaimer">Confirmación inmediata · Registro directo en base de datos.</p>
                <a class="wt-booking-card__link" href="{{ route('paquetes') }}">Ver todos los paquetes →</a>
            </aside>
        </section>
    </div>
</x-layout>
