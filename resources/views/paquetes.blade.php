<x-layout>
    <x-slot:titulo>World Travel | Paquetes turísticos</x-slot:titulo>
    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js', 'resources/css/catalogo-viajes.css'])
        <style>
            .wt-catalog .wt-page-nav { display:flex; align-items:center; justify-content:center; flex-wrap:wrap; gap:8px; }
            .wt-catalog .wt-page-link { display:inline-flex; justify-content:center; align-items:center; min-width:42px; min-height:42px; padding:9px 13px; border:1px solid #dbe5ee; border-radius:9px; background:#fff; color:#13354b; font-size:13px; font-weight:600; line-height:1.2; text-decoration:none; }
            .wt-catalog a.wt-page-link:hover { border-color:var(--ink); background:#eaf4ff; color:var(--ink); }
            .wt-catalog .wt-page-active { background:var(--ink); border-color:var(--ink); color:#fff; }
            .wt-catalog .wt-page-disabled { opacity:.48; cursor:not-allowed; }
            .wt-catalog .wt-page-summary { margin:15px 0 0; color:#748899; font-size:12px; text-align:center; }

            /* Estilo unificado de la barra de búsqueda de paquetes igual al Home */
            .wt-search-panel--unified {
                background: #fff;
                border-radius: 18px;
                padding: 1.35rem 1.5rem;
                box-shadow: 0 8px 24px rgba(12, 27, 38, 0.08);
                border: 1px solid #e2e8f0;
            }
            .wt-search-panel--unified .wt-search-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
                border-bottom: 1px solid #f1f5f9;
                padding-bottom: 0.75rem;
            }
            .wt-search-panel--unified .wt-search-header strong {
                font-size: 16px;
                font-weight: 700;
                color: #0c1b26;
            }
            .wt-search-panel--unified .wt-search-header span {
                font-size: 13px;
                color: #64748b;
                font-weight: 500;
            }
            .wt-search-grid {
                display: grid;
                grid-template-columns: minmax(0, 1.8fr) repeat(3, minmax(0, 1fr)) auto;
                gap: 0.65rem;
                align-items: center;
            }
            .wt-dd-destinations {
                width: 100%;
                min-width: 290px;
                max-height: 320px;
                overflow-y: auto;
                box-shadow: 0 10px 25px rgba(15, 30, 45, 0.12);
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 6px;
                background: #fff;
                z-index: 50;
            }
            .wt-dd-destinations .opt {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 9px 14px;
                border-radius: 8px;
                width: 100%;
                border: 0;
                background: transparent;
                cursor: pointer;
                text-align: left;
                transition: background .15s ease;
            }
            .wt-dd-destinations .opt:hover {
                background: #f1f5f9;
            }
            .wt-dd-destinations .opt .i {
                width: 16px;
                height: 16px;
                color: #106d9f;
                flex-shrink: 0;
            }
            .wt-dd-destinations .opt span {
                display: flex;
                flex-direction: column;
                font-size: 13px;
                font-weight: 700;
                color: #0f172a;
            }
            .wt-dd-destinations .opt small {
                font-size: 11px;
                color: #64748b;
                font-weight: 500;
                margin-top: 2px;
            }
            .wt-dd-destinations .opt--all {
                border-bottom: 1px solid #f1f5f9;
                margin-bottom: 4px;
                border-radius: 6px;
            }
            .wt-chip {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                background: var(--chip, #f0f3f6);
                border-radius: 10px;
                padding: 0.45rem 0.85rem;
                min-height: 48px;
                width: 100%;
                border: 0;
                cursor: pointer;
                text-align: left;
                position: relative;
                transition: box-shadow 0.2s, background 0.2s;
            }
            .wt-chip:hover {
                background: #e2e8f0;
            }
            .wt-chip[aria-expanded="true"] {
                box-shadow: 0 0 0 2px var(--ink);
            }
            .wt-chip-label {
                font-size: 10px;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                display: block;
                line-height: 1;
                margin-bottom: 3px;
            }
            .wt-chip strong {
                font-size: 13px;
                font-weight: 700;
                color: #0c1b26;
                display: block;
                line-height: 1.2;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }
            .wt-dd-menu {
                min-width: 220px;
                width: max-content;
                max-width: calc(100vw - 2rem);
            }
            @media (max-width: 1024px) {
                .wt-search-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
                .wt-search-grid > .dd-wrap--dest {
                    grid-column: span 2;
                }
                .wt-search-grid > .go {
                    grid-column: span 2;
                }
            }
            @media (max-width: 600px) {
                .wt-search-grid {
                    grid-template-columns: 1fr;
                }
                .wt-search-grid > .dd-wrap--dest,
                .wt-search-grid > .go {
                    grid-column: span 1;
                }
            }
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
                    <a class="wt-hero__quiet" href="{{ route('ofertas') }}">Ver ofertas especiales <span aria-hidden="true">↗</span></a>
                </div>
                <div class="wt-hero__trust">Destinos seleccionados <span class="wt-trust-divider">·</span> Precios en dólares estadounidenses (USD) · por persona</div>
            </div>
        </section>

        <section class="wt-container wt-search-wrap" aria-label="Buscar paquetes">
            <form class="wt-search-panel--unified" action="{{ route('paquetes') }}" method="GET">
                <div class="wt-search-header">
                    <div>
                        <strong>Encuentra el viaje perfecto</strong>
                        <span>Filtra según tus preferencias de destino, presupuesto y días</span>
                    </div>
                    @if(!empty($filtros['q']) || !empty($filtros['maximo']) || !empty($filtros['duracion']) || (!empty($filtros['orden']) && $filtros['orden'] !== 'destacados'))
                        <a href="{{ route('paquetes') }}" style="color: #106d9f; font-size: 13px; font-weight: 600; text-decoration: none;">
                            Limpiar filtros
                        </a>
                    @endif
                </div>
                <div class="wt-search-grid">
                    {{-- Destino con cinta de opciones como en Home --}}
                    <div class="dd-wrap dd-wrap--dest" style="position: relative;">
                        <label class="field" style="min-height: 48px; margin: 0; width: 100%;">
                            <svg class="i" style="width: 18px; height: 18px; color: #64748b; flex-shrink: 0;"><use href="#i-pin"/></svg>
                            <input id="paquete-destination" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="¿A dónde quieres viajar? (ej. Cusco, Cancún, Roma)" aria-label="Destino" data-dd="paquete-destination" autocomplete="off">
                        </label>
                        <div class="dd dd--list wt-dd-destinations" id="dd-paquete-destination" hidden>
                            <button type="button" class="opt opt--all" data-city="">
                                <svg class="i"><use href="#i-pin"/></svg>
                                <span>Todos los destinos <small>Explorar paquetes en todo el mundo</small></span>
                            </button>
                            @if(isset($destinosDisponibles))
                                @foreach($destinosDisponibles as $dest)
                                    <button type="button" class="opt" data-city="{{ $dest->destino }}">
                                        <svg class="i"><use href="#i-plane"/></svg>
                                        <span>{{ $dest->destino }} <small>{{ $dest->pais }}</small></span>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Presupuesto --}}
                    <div class="dd-wrap">
                        <button type="button" class="chip wt-chip" data-dd="maximo" aria-expanded="false">
                            <span class="wt-chip-label">Presupuesto</span>
                            <strong id="label-maximo">
                                @if(!empty($filtros['maximo']))
                                    Hasta US$ {{ number_format($filtros['maximo'], 0, '.', ',') }}
                                    <small style="display:block; font-size:10px; color:#64748b; font-weight:500;">aprox. S/ {{ number_format($filtros['maximo'] * 3.51, 0, '.', ',') }}</small>
                                @else
                                    Sin límite
                                @endif
                            </strong>
                        </button>
                        <div class="dd dd--list wt-dd-menu dd--custom-select" id="dd-maximo" data-name="maximo" hidden>
                            <button type="button" class="opt" data-val="" data-sub="">
                                <span>Sin límite <small>Cualquier presupuesto</small></span>
                            </button>
                            @foreach ([500, 1000, 1500, 2000, 3000] as $monto)
                                <button type="button" class="opt" data-val="{{ $monto }}" data-sub="aprox. S/ {{ number_format($monto * 3.51, 0, '.', ',') }}">
                                    <span>Hasta US$ {{ number_format($monto, 0, '.', ',') }} <small>aprox. S/ {{ number_format($monto * 3.51, 0, '.', ',') }}</small></span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="maximo" id="input-maximo" value="{{ $filtros['maximo'] ?? '' }}">
                    </div>

                    {{-- Duración --}}
                    <div class="dd-wrap">
                        <button type="button" class="chip wt-chip" data-dd="duracion" aria-expanded="false">
                            <span class="wt-chip-label">Duración</span>
                            <strong id="label-duracion">
                                @if(!empty($filtros['duracion']))
                                    Hasta {{ $filtros['duracion'] }} días
                                @else
                                    Cualquier duración
                                @endif
                            </strong>
                        </button>
                        <div class="dd dd--list wt-dd-menu dd--custom-select" id="dd-duracion" data-name="duracion" hidden>
                            <button type="button" class="opt" data-val="">
                                <span>Cualquier duración <small>Todas las opciones</small></span>
                            </button>
                            <button type="button" class="opt" data-val="3">
                                <span>Hasta 3 días <small>Escapadas y fines de semana</small></span>
                            </button>
                            <button type="button" class="opt" data-val="5">
                                <span>Hasta 5 días <small>Viajes cortos</small></span>
                            </button>
                            <button type="button" class="opt" data-val="7">
                                <span>Hasta 7 días <small>1 semana completa</small></span>
                            </button>
                            <button type="button" class="opt" data-val="10">
                                <span>Hasta 10 días <small>Vacaciones extendidas</small></span>
                            </button>
                        </div>
                        <input type="hidden" name="duracion" id="input-duracion" value="{{ $filtros['duracion'] ?? '' }}">
                    </div>

                    {{-- Ordenar --}}
                    <div class="dd-wrap">
                        <button type="button" class="chip wt-chip" data-dd="orden" aria-expanded="false">
                            <span class="wt-chip-label">Ordenar</span>
                            <strong id="label-orden">
                                @php
                                    $ordenMap = [
                                        'destacados' => 'Destacados',
                                        'precio_asc' => 'Menor precio',
                                        'precio_desc' => 'Mayor precio',
                                        'duracion' => 'Menor duración'
                                    ];
                                @endphp
                                {{ $ordenMap[$filtros['orden'] ?? 'destacados'] ?? 'Destacados' }}
                            </strong>
                        </button>
                        <div class="dd dd--list wt-dd-menu dd--custom-select" id="dd-orden" data-name="orden" hidden>
                            <button type="button" class="opt" data-val="destacados">
                                <span>Destacados <small>Recomendados por World Travel</small></span>
                            </button>
                            <button type="button" class="opt" data-val="precio_asc">
                                <span>Menor precio <small>De más económico a premium</small></span>
                            </button>
                            <button type="button" class="opt" data-val="precio_desc">
                                <span>Mayor precio <small>Experiencias exclusivas</small></span>
                            </button>
                            <button type="button" class="opt" data-val="duracion">
                                <span>Menor duración <small>Menos días de viaje</small></span>
                            </button>
                        </div>
                        <input type="hidden" name="orden" id="input-orden" value="{{ $filtros['orden'] ?? 'destacados' }}">
                    </div>

                    <button class="go" type="submit" style="min-height: 48px; border-radius: 10px; padding: 0 1.4rem; white-space: nowrap;">
                        <svg class="i"><use href="#i-search"/></svg>
                        <span>Buscar paquetes</span>
                    </button>
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
                <div class="wt-empty"><h3>No encontramos paquetes con esos filtros.</h3><p>Prueba otro destino o aumenta tu presupuesto.</p><a class="btn-dark" href="{{ route('paquetes') }}" style="display:inline-block; margin-top: 1rem;">Ver todos</a></div>
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
                                <p class="wt-card__location">{{ $paquete->destino }}, {{ $paquete->pais }}</p>
                                <h3>{{ $paquete->titulo }}</h3>
                                <p class="wt-card__desc">{{ $paquete->descripcion }}</p>
                                <div class="wt-card__perks">
                                    @if ($paquete->incluye_hotel)<span>Alojamiento</span>@endif
                                    @if ($paquete->incluye_vuelo)<span>Vuelo</span>@endif
                                    @if ($paquete->incluye_tour)<span>Actividades</span>@endif
                                </div>
                                <div class="wt-card__bottom">
                                    <div>
                                        <span class="wt-price-label">Desde / por persona</span>
                                        <div class="wt-price">US$ {{ number_format((float) $paquete->precio, 2, '.', ',') }}</div>
                                        <small style="display:block; font-size:11px; color:#64748b; font-weight:500; margin-top:2px;">aprox. S/ {{ number_format((float) $paquete->precio * 3.51, 2, '.', ',') }}</small>
                                    </div>
                                    <a class="btn-ver-detalles" href="{{ route('paquetes.detalle', $paquete) }}">Ver detalles</a>
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
                        <p class="wt-page-summary">Mostrando {{ $paquetes->firstItem() }} a {{ $paquetes->lastItem() }} de {{ $paquetes->total() }} paquetes turísticos</p>
                    @endif
                </div>
            @endif
        </section>

        <section class="wt-container wt-bottom-banner">
            <div><span class="wt-kicker">¿NO ENCUENTRAS LO QUE BUSCAS?</span><h2>Diseña tu experiencia a medida.</h2><p>Combina vuelos, noches de hotel y traslados según tu presupuesto.</p></div>
            <a class="hero__btn" href="{{ route('itinerarios.index') }}" style="display: inline-flex; align-items: center; background: #fff; color: #0b2535; padding: .65rem 1.4rem; border-radius: 10px; font-weight: 700; font-size: .95rem; text-decoration: none;">Crear itinerario</a>
        </section>
        <p class="wt-catalog-note">Precios referenciales en dólares estadounidenses (USD) basados en disponibilidad de demostración. Sujetos a cambios según fechas.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cinta de opciones para el destino (como en Home)
            const destInput = document.getElementById('paquete-destination');
            const destDd = document.getElementById('dd-paquete-destination');

            if (destInput && destDd) {
                destInput.addEventListener('focus', () => {
                    destDd.hidden = false;
                });
                destInput.addEventListener('click', (e) => {
                    e.stopPropagation();
                    destDd.hidden = false;
                });
                destInput.addEventListener('input', () => {
                    const q = destInput.value.toLowerCase().trim();
                    destDd.hidden = false;
                    destDd.querySelectorAll('.opt').forEach(btn => {
                        const city = (btn.dataset.city || '').toLowerCase();
                        const text = btn.textContent.toLowerCase();
                        btn.style.display = (!q || city.includes(q) || text.includes(q)) ? 'flex' : 'none';
                    });
                });
                destDd.addEventListener('click', (e) => {
                    const btn = e.target.closest('.opt');
                    if (!btn) return;
                    destInput.value = btn.dataset.city || '';
                    destDd.hidden = true;
                });
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.dd-wrap--dest')) {
                        destDd.hidden = true;
                    }
                });
            }

            // Selectores personalizados para Presupuesto, Duración y Orden
            document.querySelectorAll('.dd--custom-select').forEach(dd => {
                dd.addEventListener('click', (e) => {
                    const opt = e.target.closest('.opt');
                    if (!opt) return;
                    const name = dd.dataset.name;
                    const val = opt.dataset.val;
                    const input = document.getElementById('input-' + name);
                    const label = document.getElementById('label-' + name);
                    if (input) input.value = val;
                    if (label) {
                        const titleSpan = opt.querySelector('span');
                        const mainTitle = titleSpan ? titleSpan.childNodes[0].textContent.trim() : opt.textContent.trim();
                        const sub = opt.dataset.sub;
                        if (sub) {
                            label.innerHTML = mainTitle + `<small style="display:block; font-size:10px; color:#64748b; font-weight:500; margin-top:2px;">${sub}</small>`;
                        } else {
                            label.textContent = mainTitle;
                        }
                    }
                    dd.hidden = true;
                    const btn = document.querySelector(`[data-dd="${name}"]`);
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                });
            });
        });
    </script>
</x-layout>
