<x-layout>
    <x-slot:titulo>World Travel | Ofertas y promociones</x-slot:titulo>
    <x-slot:recursos>
        @vite(['resources/css/catalogo-viajes.css'])
    </x-slot:recursos>

    <div class="wt-catalog">
        <section class="wt-hero wt-hero--ofertas">
            <div class="wt-container wt-hero__inside">
                <div class="wt-hero__eyebrow"><span class="wt-dot"></span> PRECIOS ESPECIALES</div>
                <h1>Más destinos.<br><em>Menos límites.</em></h1>
                <p>Descubre promociones internacionales con precios expresados en dólares estadounidenses (USD).</p>
                <div class="wt-hero__actions">
                    <a class="wt-button wt-button--white" href="#ofertas-vigentes">Ver promociones <span aria-hidden="true">→</span></a>
                    <a class="wt-hero__quiet" href="{{ route('paquetes') }}">Todos los paquetes <span aria-hidden="true">↗</span></a>
                </div>
            </div>
            <div class="wt-hero-offer-stat"><strong>HASTA</strong><span>{{ $ofertas->max('descuento_porcentaje') ?? 0 }}%</span><small>en promociones de prueba</small></div>
        </section>

        <section class="wt-container wt-section" id="ofertas-vigentes">
            <div class="wt-heading-row">
                <div><div class="wt-kicker">APROVECHA HOY</div><h2>Ofertas que te acercan <span>a tu destino</span></h2><p>Solo se muestran promociones activas y dentro de su fecha de vigencia.</p></div>
                <span class="wt-result-count">{{ $ofertas->count() }} {{ $ofertas->count() === 1 ? 'promoción' : 'promociones' }}</span>
            </div>

            @if ($ofertas->isEmpty())
                <div class="wt-empty"><h3>No hay ofertas vigentes en este momento.</h3><p>Puedes revisar todos los paquetes disponibles.</p><a class="wt-button wt-button--blue" href="{{ route('paquetes') }}">Explorar paquetes</a></div>
            @else
                <div class="wt-cards">
                    @foreach ($ofertas as $oferta)
                        @php
                            $precioOriginal = (float) $oferta->paquete->precio;
                            $precioFinal = round($precioOriginal * (1 - $oferta->descuento_porcentaje / 100), 2);
                        @endphp
                        <article class="wt-card wt-card--offer">
                            <div class="wt-card__media">
                                @if ($oferta->paquete->imagen_url)<img src="{{ $oferta->paquete->imagen_url }}" alt="Paisaje ilustrativo de {{ $oferta->paquete->destino }}" loading="lazy">@endif
                                <span class="wt-card__discount">-{{ $oferta->descuento_porcentaje }}%</span>
                                <span class="wt-card__duration">{{ $oferta->paquete->duracion_dias }} días de viaje</span>
                            </div>
                            <div class="wt-card__content">
                                <p class="wt-card__location"><span aria-hidden="true">⌖</span> {{ $oferta->paquete->destino }}, {{ $oferta->paquete->pais }}</p>
                                <h3>{{ $oferta->titulo }}</h3>
                                <p class="wt-card__desc">{{ $oferta->paquete->descripcion }}</p>
                                <div class="wt-offer-expiry">Promoción vigente hasta el {{ $oferta->fecha_fin->format('d/m/Y') }}</div>
                                <div class="wt-card__bottom">
                                    <div>
                                        <span class="wt-price-label">Antes <s>US$ {{ number_format($precioOriginal, 2, '.', ',') }}</s></span>
                                        <div class="wt-price wt-price--offer">US$ {{ number_format($precioFinal, 2, '.', ',') }}</div>
                                        <small>Por persona · precio de prueba</small>
                                    </div>
                                    <a class="wt-button wt-button--small" href="{{ route('paquetes.detalle', $oferta->paquete) }}">Ver paquete <span aria-hidden="true">→</span></a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="wt-container wt-bottom-banner">
            <div><span class="wt-kicker">¿TODAVÍA EXPLORANDO?</span><h2>Encuentra tu experiencia ideal.</h2><p>Compara duraciones, destinos y presupuestos.</p></div>
            <a class="wt-button wt-button--white" href="{{ route('paquetes') }}">Ver todos los paquetes <span aria-hidden="true">→</span></a>
        </section>
        <p class="wt-catalog-note">Todas las promociones están expresadas en USD y son datos de demostración. No hay cobro, pago ni reserva real en este módulo.</p>
    </div>
</x-layout>
