<x-layout>
    <x-slot:titulo>World Travel | Paquetes turísticos</x-slot:titulo>
    <x-slot:recursos>
        @vite(['resources/css/catalogo-viajes.css'])
        <style>
            .wt-catalog .wt-page-nav { display:flex; align-items:center; justify-content:center; flex-wrap:wrap; gap:8px; }
            .wt-catalog .wt-page-link { display:inline-flex; justify-content:center; align-items:center; min-width:42px; min-height:42px; padding:9px 13px; border:1px solid #dbe5ee; border-radius:9px; background:#fff; color:#13354b; font-size:13px; font-weight:600; line-height:1.2; text-decoration:none; }
            .wt-catalog a.wt-page-link:hover { border-color:#0875e9; background:#eaf4ff; color:#0875e9; }
            .wt-catalog .wt-page-active { background:#0875e9; border-color:#0875e9; color:#fff; }
            .wt-catalog .wt-page-disabled { opacity:.48; cursor:not-allowed; }
            .wt-catalog .wt-page-summary { margin:15px 0 0; color:#748899; font-size:12px; text-align:center; }
            @media (max-width:520px) { .wt-catalog .wt-page-link { min-height:36px; min-width:36px; padding:8px; font-size:12px; } }
        </style>
    </x-slot:recursos>

    <div class="wt-catalog">
        <section class="wt-hero wt-hero--paquetes">
            <div class="wt-container wt-hero__inside">
                <div class="wt-hero__eyebrow"><span class="wt-dot"></span> DESCUBRE WORLD TRAVEL</div>
                <h1>Tu próximo destino<br><em>está más cerca.</em></h1>
                <p>Encuentra experiencias que combinan alojamiento, actividades y mucho más. Descubre América, Europa, Asia, África y Oceanía. Elige cómo quieres viajar.</p>
                <div class="wt-hero__actions">
                    <a class="wt-button wt-button--white" href="#explorar-paquetes">Explorar paquetes <span aria-hidden="true">→</span></a>
                    <a class="wt-hero__quiet" href="{{ route('ofertas') }}">Ver ofertas especiales <span aria-hidden="true">↗</span></a>
                </div>
                <div class="wt-hero__trust"><span>✦</span> Destinos seleccionados <span class="wt-trust-divider">·</span> Precios en dólares estadounidenses (USD) · por persona</div>
            </div>
        </section>

        <section class="wt-container wt-search-wrap" aria-label="Buscar paquetes">
            <form class="wt-search-panel" action="{{ route('paquetes') }}" method="GET">
                <div class="wt-search-header"><strong>Encuentra el viaje perfecto</strong><span>Filtra según tus preferencias</span></div>
                <div class="wt-search-fields">
                    <label class="wt-field wt-field--wide"><span>Destino</span>
                        <input type="search" name="q" value="{{ $filtros['q'] ?? '' }}" maxlength="100" placeholder="¿A dónde quieres viajar?">
                    </label>
                    <label class="wt-field"><span>Región del mundo</span>
                        <select name="continente">
                            <option value="">Todos los continentes</option>
                            @foreach (['America' => 'América', 'Europa' => 'Europa', 'Asia' => 'Asia', 'Africa' => 'África', 'Oceania' => 'Oceanía'] as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(($filtros['continente'] ?? '') === $valor)>{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="wt-field"><span>Presupuesto máximo (USD)</span>
                        <select name="maximo">
                            <option value="">Sin límite</option>
                            @foreach ([300, 600, 900, 1200, 1800, 2500, 3500] as $monto)
                                <option value="{{ $monto }}" @selected(($filtros['maximo'] ?? '') == $monto)>Hasta US$ {{ number_format($monto, 0, '.', ',') }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="wt-field"><span>Duración</span>
                        <select name="duracion">
                            <option value="">Cualquier duración</option>
                            @foreach ([3, 5, 7, 10, 14] as $dias)
                                <option value="{{ $dias }}" @selected(($filtros['duracion'] ?? '') == $dias)>Hasta {{ $dias }} días</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="wt-field"><span>Ordenar</span>
                        <select name="orden">
                            <option value="destacados" @selected(($filtros['orden'] ?? 'destacados') === 'destacados')>Destacados</option>
                            <option value="precio_asc" @selected(($filtros['orden'] ?? '') === 'precio_asc')>Menor precio</option>
                            <option value="precio_desc" @selected(($filtros['orden'] ?? '') === 'precio_desc')>Mayor precio</option>
                            <option value="duracion" @selected(($filtros['orden'] ?? '') === 'duracion')>Menor duración</option>
                        </select>
                    </label>
                    <button class="wt-button wt-button--blue wt-search-submit" type="submit">Buscar paquetes <span aria-hidden="true">→</span></button>
                </div>
            </form>
        </section>

        <section class="wt-container wt-section" id="explorar-paquetes">
            <div class="wt-heading-row">
                <div>
                    <div class="wt-kicker">EXPERIENCIAS PARA TI</div>
                    <h2>Paquetes para descubrir <span>el mundo</span></h2>
                    <p>Explora destinos internacionales y nacionales, con precios referenciales en dólares estadounidenses.</p>
                </div>
                <span class="wt-result-count">{{ $paquetes->total() }} {{ $paquetes->total() === 1 ? 'paquete' : 'paquetes' }}</span>
            </div>

            @if ($paquetes->isEmpty())
                <div class="wt-empty"><h3>No encontramos paquetes con esos filtros.</h3><p>Prueba otro destino o aumenta tu presupuesto.</p><a class="wt-button wt-button--blue" href="{{ route('paquetes') }}">Ver todos</a></div>
            @else
                <div class="wt-cards">
                    @foreach ($paquetes as $paquete)
                        <article class="wt-card">
                            <div class="wt-card__media">
                                @if ($paquete->imagen_url)
                                    <img src="{{ $paquete->imagen_url }}" alt="Paisaje ilustrativo de {{ $paquete->destino }}" loading="lazy">
                                @endif
                                @if ($paquete->destacado)<span class="wt-card__tag">Destacado</span>@endif
                                <span class="wt-card__duration">{{ $paquete->duracion_dias }} días / {{ max(0, $paquete->duracion_dias - 1) }} noches</span>
                            </div>
                            <div class="wt-card__content">
                                <p class="wt-card__location"><span aria-hidden="true">⌖</span> {{ $paquete->destino }}, {{ $paquete->pais }}</p>
                                <h3>{{ $paquete->titulo }}</h3>
                                <p class="wt-card__desc">{{ $paquete->descripcion }}</p>
                                <div class="wt-card__perks">
                                    @if ($paquete->incluye_hotel)<span>Alojamiento</span>@endif
                                    @if ($paquete->incluye_vuelo)<span>Vuelo</span>@endif
                                    @if ($paquete->incluye_tour)<span>Actividades</span>@endif
                                </div>
                                <div class="wt-card__bottom">
                                    <div><span class="wt-price-label">Desde / por persona</span><div class="wt-price">US$ {{ number_format((float) $paquete->precio, 2, '.', ',') }}</div></div>
                                    <a class="wt-button wt-button--small" href="{{ route('paquetes.detalle', $paquete) }}">Ver detalles <span aria-hidden="true">→</span></a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                {{-- Paginación personalizada: sin SVG de Tailwind --}}
                <div class="wt-pagination">
                    @if ($paquetes->hasPages())
                        <nav class="wt-page-nav" aria-label="Paginación de paquetes">
                            @if ($paquetes->onFirstPage())
                                <span class="wt-page-link wt-page-disabled">← Anterior</span>
                            @else
                                <a class="wt-page-link" href="{{ $paquetes->previousPageUrl() }}">← Anterior</a>
                            @endif

                            @foreach ($paquetes->getUrlRange(1, $paquetes->lastPage()) as $numero => $url)
                                @if ($numero == $paquetes->currentPage())
                                    <span class="wt-page-link wt-page-active" aria-current="page">{{ $numero }}</span>
                                @else
                                    <a class="wt-page-link" href="{{ $url }}">{{ $numero }}</a>
                                @endif
                            @endforeach

                            @if ($paquetes->hasMorePages())
                                <a class="wt-page-link" href="{{ $paquetes->nextPageUrl() }}">Siguiente →</a>
                            @else
                                <span class="wt-page-link wt-page-disabled">Siguiente →</span>
                            @endif
                        </nav>
                    @endif
                    <p class="wt-page-summary">
                        Mostrando {{ $paquetes->firstItem() ?? 0 }} a {{ $paquetes->lastItem() ?? 0 }} de {{ $paquetes->total() }} paquetes
                    </p>
                </div>
            @endif
        </section>

        <section class="wt-container wt-bottom-banner">
            <div><span class="wt-kicker">OPORTUNIDADES ESPECIALES</span><h2>Viaja más. Aprovecha nuestras ofertas.</h2><p>Descubre descuentos disponibles por tiempo limitado.</p></div>
            <a class="wt-button wt-button--white" href="{{ route('ofertas') }}">Explorar ofertas <span aria-hidden="true">→</span></a>
        </section>
        <p class="wt-catalog-note">Todos los precios de este catálogo están expresados en USD y son datos de demostración para World Travel. No representan disponibilidad ni reservas confirmadas.</p>
    </div>
</x-layout>
