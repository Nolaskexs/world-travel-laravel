
<x-layout>
    <x-slot:titulo>World Travel | Alojamientos</x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <div class="wt-hoteles">
        <section class="hotel-hero">
            <div class="hotel-container">
                <span class="hotel-etiqueta">WORLD TRAVEL</span>
                <h1>Encuentra tu alojamiento ideal</h1>
                <p>Descubre lugares increíbles para descansar durante tu próximo viaje.</p>
            </div>
        </section>

        <section class="hotel-container" style="position: relative; z-index: 20;">
            <form class="sf sf--hotel" id="formHoteles" method="GET" action="{{ route('alojamientos') }}" autocomplete="off" style="margin-top: -38px; position: relative;">
                <input type="hidden" name="entrada" id="inputStart" value="{{ request('entrada', '') }}">
                <input type="hidden" name="salida" id="inputEnd" value="{{ request('salida', '') }}">
                <input type="hidden" name="huespedes" id="inputAdults" value="{{ request('huespedes', '1') }}">
                <input type="hidden" name="kids" id="inputKids" value="{{ request('kids', '0') }}">
                <input type="hidden" name="infants" id="inputInfants" value="{{ request('infants', '0') }}">

                <div class="sf__top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <strong style="font-size: 16px; font-weight: 700; color: #0c1b26; display: block;">Encuentra tu alojamiento ideal</strong>
                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Filtra y reserva entre los mejores hoteles y hospedajes</span>
                    </div>

                    <div class="dd-wrap">
                        <button type="button" class="chip" data-dd="pax" aria-expanded="false">
                            <svg class="i"><use href="#i-user"/></svg>
                            <span id="paxLabel" data-mode="hotel">{{ request('huespedes', 1) == 1 ? '1 persona' : request('huespedes', 1) . ' huéspedes' }}</span>
                        </button>
                        <div class="dd dd--pax" id="dd-pax" hidden>
                            <div class="prow" data-pax="adults">
                                <div><strong>Adultos</strong><small>Mayores de 12 años</small></div>
                                <div class="cnt">
                                    <button type="button" data-step="-1" aria-label="Quitar adulto"><svg class="i"><use href="#i-minus"/></svg></button>
                                    <output>{{ request('huespedes', 1) }}</output>
                                    <button type="button" data-step="1" aria-label="Agregar adulto"><svg class="i"><use href="#i-plus"/></svg></button>
                                </div>
                            </div>
                            <div class="prow" data-pax="kids">
                                <div><strong>Menores</strong><small>De 2 a 11 años</small></div>
                                <div class="cnt">
                                    <button type="button" data-step="-1" aria-label="Quitar menor"><svg class="i"><use href="#i-minus"/></svg></button>
                                    <output>{{ request('kids', 0) }}</output>
                                    <button type="button" data-step="1" aria-label="Agregar menor"><svg class="i"><use href="#i-plus"/></svg></button>
                                </div>
                            </div>
                            <div class="pax-actions">
                                <button type="button" class="btn-ghost" data-act="cancel">Cancelar</button>
                                <button type="button" class="btn-dark" data-act="apply">Aplicar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sf__row sf__row--hotel">
                    <div class="dd-wrap" style="flex: 1.4; min-width: 220px;">
                        <label class="field" style="width: 100%;">
                            <svg class="i"><use href="#i-pin"/></svg>
                            <input id="destination" name="q" value="{{ $filtros['q'] ?? request('destination', '') }}" placeholder="¿A dónde viajas? (ej. Tokio, Madrid, Cusco...)" aria-label="Destino" data-dd="destination" autocomplete="off">
                        </label>
                        <div class="dd dd--list" id="dd-destination" hidden></div>
                    </div>

                    <div class="dates dd-wrap" style="flex: 1.6; min-width: 260px;">
                        <button type="button" class="field" id="startBtn" data-dd="cal" data-field="start" aria-expanded="false" style="flex: 1;">
                            <svg class="i"><use href="#i-cal"/></svg>
                            <span id="startLabel" data-label="Entrada">{{ request('entrada') ?: 'Entrada' }}</span>
                        </button>
                        <button type="button" class="field" id="endBtn" data-dd="cal" data-field="end" aria-expanded="false" style="flex: 1;">
                            <svg class="i"><use href="#i-cal"/></svg>
                            <span id="endLabel" data-label="Salida">{{ request('salida') ?: 'Salida' }}</span>
                        </button>
                        <div class="dd dd--cal" id="dd-cal" data-field="start" hidden>
                            <div class="cal">
                                <button type="button" class="cal__nav cal__prev" aria-label="Mes anterior"><svg class="i"><use href="#i-left"/></svg></button>
                                <button type="button" class="cal__nav cal__next" aria-label="Mes siguiente"><svg class="i"><use href="#i-right"/></svg></button>
                                <div class="cal__m"></div>
                                <div class="cal__m"></div>
                            </div>
                        </div>
                    </div>

                    <button class="go" type="submit" style="min-width: 130px;">
                        <svg class="i"><use href="#i-search"/></svg>
                        <span>Buscar</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="hotel-container hotel-contenido">
            <div class="hotel-encabezado">
                <div>
                    <span class="hotel-etiqueta texto-azul">EXPLORA Y DESCUBRE</span>
                    <h2>Alojamientos destacados</h2>
                    <p>Opciones reales conectadas a nuestra base de datos MySQL.</p>
                </div>
            </div>

            <p id="hotelContador" class="hotel-contador">
                {{ $alojamientos->count() }} {{ $alojamientos->count() === 1 ? 'alojamiento encontrado' : 'alojamientos encontrados' }} en nuestra base de datos
            </p>

            <div class="hotel-grid" id="listaHoteles">
                @forelse ($alojamientos as $alojamiento)
                    @php
                        $esSoles = ($alojamiento->moneda_simbolo ?? 'US$') === 'S/';
                        $precioUsd = $esSoles ? round((float) $alojamiento->precio / 3.51) : (float) $alojamiento->precio;
                        $precioSoles = $esSoles ? (float) $alojamiento->precio : round((float) $alojamiento->precio * 3.51, 2);
                        $imagenTarjeta = $alojamiento->imagen_tarjeta ?: $alojamiento->imagen ?: 'icons/hotel1.jpg';
                        $imgSrc = str_starts_with($imagenTarjeta, 'http') ? $imagenTarjeta : asset(ltrim($imagenTarjeta, '/'));
                    @endphp

                    <article class="hotel-card"
                             data-destino="{{ strtolower($alojamiento->palabras_clave ?? $alojamiento->ubicacion ?? $alojamiento->nombre) }}"
                             data-tipo="{{ strtolower($alojamiento->categoria ?? $alojamiento->tipo ?? 'hotel') }}">
                        <img src="{{ $imgSrc }}"
                             alt="{{ $alojamiento->nombre }}"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='{{ asset('icons/hotel1.jpg') }}';">

                        <div class="hotel-info">
                            <span class="hotel-tipo">{{ strtoupper($alojamiento->tipo) }}</span>
                            <h3>{{ $alojamiento->nombre }}</h3>
                            <p class="hotel-ubicacion">{{ $alojamiento->ubicacion }}</p>
                            <p class="hotel-servicios">{{ $alojamiento->servicios_resumen }}</p>

                            <div class="hotel-footer">
                                <div>
                                    <small>Precio referencial / noche</small>
                                    @if($esSoles)
                                        <strong>S/ {{ number_format($precioSoles, 0, '.', ',') }}</strong>
                                        <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. US$ {{ number_format($precioUsd, 2, '.', ',') }}</small>
                                    @else
                                        <strong>US$ {{ number_format($precioUsd, 0, '.', ',') }}</strong>
                                        <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ {{ number_format($precioSoles, 2, '.', ',') }}</small>
                                    @endif
                                </div>

                                <a href="{{ route('alojamientos.detalle', ['codigo' => $alojamiento->codigo]) }}"
                                   class="hotel-ver">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="hotel-vacio" style="grid-column: 1 / -1; display: block; text-align: center; padding: 40px 20px;">
                        No encontramos alojamientos registrados que coincidan con la búsqueda.
                    </p>
                @endforelse
            </div>
            <p id="hotelVacio" class="hotel-vacio" hidden>
                No encontramos alojamientos para ese destino o tipo.
            </p>
        </section>
    </div>

    <style>
        .wt-hoteles {
            background: #f3f5f8;
            color: #102331;
            font-family: 'Inter', sans-serif;
            min-height: 75vh;
        }

        .wt-hoteles * {
            box-sizing: border-box;
        }

        .hotel-container {
            max-width: 1120px;
            margin: auto;
            padding-left: 20px;
            padding-right: 20px;
        }

        .hotel-hero {
            background: linear-gradient(115deg, #0a1d29, #19516c);
            color: white;
            padding: 66px 0 105px;
        }

        .hotel-etiqueta {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .hotel-hero h1 {
            font-size: clamp(28px, 4vw, 42px);
            margin: 0 0 12px;
            font-weight: 700;
        }

        .hotel-hero p {
            color: #d9e3ec;
            font-size: 15px;
        }

        .sf--hotel {
            background: #fff;
            border-radius: 18px;
            padding: 1.25rem;
            margin-top: -48px;
            box-shadow: 0 9px 28px rgba(15, 30, 45, .1);
            position: relative;
        }

        @media (min-width: 900px) {
            .sf__row--hotel {
                display: grid !important;
                grid-template-columns: 1.4fr 1.6fr auto !important;
                gap: 0.6rem !important;
                align-items: center !important;
            }
        }

        .hotel-campo label,
        .hotel-filtro label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .hotel-campo input,
        .hotel-campo select,
        .hotel-filtro select {
            width: 100%;
            height: 43px;
            border: 1px solid #dce2e8;
            border-radius: 8px;
            padding: 0 10px;
            background: white;
            color: #152b3d;
            font: inherit;
            font-size: 13px;
        }

        .hotel-boton, .hotel-ver {
            background: #0b2535;
            color: white;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
        }

        .hotel-boton {
            min-height: 43px;
            padding: 0 22px;
        }

        .hotel-boton:hover, .hotel-ver:hover {
            background: #1b5876;
        }

        .hotel-contenido {
            padding-top: 55px;
            padding-bottom: 75px;
        }

        .hotel-encabezado {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            margin-bottom: 20px;
        }

        .texto-azul { color: #286789; }

        .hotel-encabezado h2 {
            font-size: 27px;
            margin: 0 0 8px;
        }

        .hotel-encabezado p,
        .hotel-ubicacion,
        .hotel-servicios,
        .hotel-contador {
            color: #647482;
            font-size: 13px;
        }

        .hotel-filtro select { min-width: 180px; }
        .hotel-contador { margin-bottom: 18px; }

        .hotel-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .hotel-card {
            background: white;
            border: 1px solid #e6eaee;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(15, 30, 45, .04);
            transition: transform .2s, box-shadow .2s;
        }

        .hotel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(15, 30, 45, .11);
        }

        .hotel-card > img {
            width: 100%;
            height: 195px;
            object-fit: cover;
            display: block;
        }

        .hotel-info { padding: 20px; }

        .hotel-tipo {
            font-size: 10px;
            font-weight: 700;
            color: #256888;
            letter-spacing: 1px;
        }

        .hotel-info h3 {
            font-size: 18px;
            margin: 9px 0;
        }

        .hotel-servicios {
            margin: 18px 0;
        }

        .hotel-footer {
            border-top: 1px solid #edf0f3;
            padding-top: 17px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .hotel-footer small {
            display: block;
            font-size: 10px;
            color: #7b8792;
            margin-bottom: 5px;
        }

        .hotel-footer strong {
            font-size: 21px;
            color: #0b2535;
        }

        .hotel-ver { 
            padding: 11px 13px;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .hotel-vacio {
            background: white;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
        }

        @media (max-width: 900px) {
            .hotel-buscador {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .hotel-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .hotel-buscador,
            .hotel-grid {
                grid-template-columns: 1fr;
            }

            .hotel-encabezado {
                flex-direction: column;
                align-items: stretch;
            }

            .hotel-hero { padding-top: 45px; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formHoteles');
            const destino = document.getElementById('destination');
            const tarjetas = [...document.querySelectorAll('.hotel-card')];
            const contador = document.getElementById('hotelContador');
            const vacio = document.getElementById('hotelVacio');

            function filtrar() {
                const ciudad = (destino ? destino.value : '').trim().toLowerCase();
                let visibles = 0;

                tarjetas.forEach(tarjeta => {
                    const coincideCiudad =
                        !ciudad || (tarjeta.dataset.destino || '').toLowerCase().includes(ciudad);

                    tarjeta.hidden = !coincideCiudad;
                    if (coincideCiudad) visibles++;
                });

                if (contador) {
                    contador.textContent =
                        visibles + (visibles === 1 ? ' alojamiento disponible' : ' alojamientos disponibles');
                }

                if (vacio) {
                    vacio.hidden = visibles !== 0;
                }
            }

            if (form) {
                form.addEventListener('submit', (evento) => {
                    evento.preventDefault();
                    filtrar();
                });
            }

            if (destino) {
                destino.addEventListener('input', filtrar);
                const ddDest = document.getElementById('dd-destination');
                if (ddDest) {
                    ddDest.addEventListener('click', () => {
                        setTimeout(filtrar, 50);
                    });
                }
            }
        });
    </script>
</x-layout>
