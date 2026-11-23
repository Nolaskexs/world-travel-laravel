
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
            <form class="sf sf--hotel" id="formHoteles" autocomplete="off" style="margin-top: -38px; position: relative;">
                <input type="hidden" name="entrada" id="inputStart" value="">
                <input type="hidden" name="salida" id="inputEnd" value="">
                <input type="hidden" name="huespedes" id="inputAdults" value="2">
                <input type="hidden" name="kids" id="inputKids" value="0">
                <input type="hidden" name="infants" id="inputInfants" value="0">

                <div class="sf__top" style="justify-content: flex-end; margin-bottom: 0.35rem;">
                    <div class="dd-wrap">
                        <button type="button" class="chip" data-dd="pax" aria-expanded="false">
                            <svg class="i"><use href="#i-user"/></svg>
                            <span id="paxLabel" data-mode="hotel">2 huéspedes</span>
                        </button>
                        <div class="dd dd--pax" id="dd-pax" hidden>
                            <div class="prow" data-pax="adults">
                                <div><strong>Adultos</strong><small>Mayores de 12 años</small></div>
                                <div class="cnt">
                                    <button type="button" data-step="-1" aria-label="Quitar adulto"><svg class="i"><use href="#i-minus"/></svg></button>
                                    <output>2</output>
                                    <button type="button" data-step="1" aria-label="Agregar adulto"><svg class="i"><use href="#i-plus"/></svg></button>
                                </div>
                            </div>
                            <div class="prow" data-pax="kids">
                                <div><strong>Menores</strong><small>De 2 a 11 años</small></div>
                                <div class="cnt">
                                    <button type="button" data-step="-1" aria-label="Quitar menor"><svg class="i"><use href="#i-minus"/></svg></button>
                                    <output>0</output>
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
                            <input id="destination" name="destination" value="" placeholder="¿A dónde viajas? (ej. Tokio, Madrid, Cusco...)" aria-label="Destino" data-dd="destination" autocomplete="off">
                        </label>
                        <div class="dd dd--list" id="dd-destination" hidden></div>
                    </div>

                    <div class="dates dd-wrap" style="flex: 1.6; min-width: 260px;">
                        <button type="button" class="field" id="startBtn" data-dd="cal" data-field="start" aria-expanded="false" style="flex: 1;">
                            <svg class="i"><use href="#i-cal"/></svg>
                            <span id="startLabel" data-label="Entrada">Entrada</span>
                        </button>
                        <button type="button" class="field" id="endBtn" data-dd="cal" data-field="end" aria-expanded="false" style="flex: 1;">
                            <svg class="i"><use href="#i-cal"/></svg>
                            <span id="endLabel" data-label="Salida">Salida</span>
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
                    <p>Opciones para disfrutar de tu próximo destino.</p>
                </div>

                <div class="hotel-filtro">
                    <label for="tipoHotel">Tipo de alojamiento</label>
                    <select id="tipoHotel">
                        <option value="todos">Todos</option>
                        <option value="hotel">Hoteles</option>
                        <option value="resort">Resorts</option>
                        <option value="hostal">Hostales</option>
                    </select>
                </div>
            </div>

            <p id="hotelContador" class="hotel-contador">
                9 alojamientos disponibles en esta demostración
            </p>

            <div class="hotel-grid" id="listaHoteles">

                <article class="hotel-card"
                         data-destino="tokio japon japón tokyo"
                         data-tipo="hotel">
                    <img
                        src="{{ asset('icons/hotel1.jpg') }}"
                        alt="The Ritz-Carlton, Tokyo"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">HOTEL DE LUJO</span>
                        <h3>The Ritz-Carlton, Tokyo</h3>
                        <p class="hotel-ubicacion">Tokio, Japón (Roppongi)</p>
                        <p class="hotel-servicios">
                            Wi-Fi · Spa Michelin · Piscina · Vista Mt. Fuji
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 19,957</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 70,049</small>
                            </div>

                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'tokio-ritz']) }}"
                            class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="cartagena colombia"
                         data-tipo="hotel">
                    <img
                        src="{{ asset('icons/hotel3.jpg') }}"
                        alt="Oz Hotel Luxury"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">HOTEL BOUTIQUE</span>
                        <h3>Oz Hotel Luxury</h3>
                        <p class="hotel-ubicacion">Cartagena de Indias, Colombia</p>
                        <p class="hotel-servicios">
                            Desayuno buffet · Rooftop lounge · Wi-Fi
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 74</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 259.74</small>
                            </div>

                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'cartagena-oz']) }}"
                            class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="madrid espana españa"
                         data-tipo="hotel">
                    <img
                        src="{{ asset('icons/hotel2.jpg') }}"
                        alt="Hotel Riu Plaza España"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">HOTEL</span>
                        <h3>Hotel Riu Plaza España</h3>
                        <p class="hotel-ubicacion">Madrid, España (Gran Vía)</p>
                        <p class="hotel-servicios">
                            Sky Bar 360° · Desayuno gourmet · Piscina
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 268</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 940.68</small>
                            </div>

                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'madrid-riu']) }}"
                            class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="cusco peru perú machu picchu"
                         data-tipo="hotel">
                    <img
                        src="{{ asset('icons/belmond-hotel-monasterio.jpg') }}"
                        alt="Belmond Monasterio, Cusco"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">HOTEL HISTÓRICO</span>
                        <h3>Belmond Monasterio, Cusco</h3>
                        <p class="hotel-ubicacion">Cusco, Perú (Plazoleta Nazarenas)</p>
                        <p class="hotel-servicios">
                            Oxígeno enriquecido · Desayuno · Arte virreinal
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 540</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 1,895.40</small>
                            </div>

                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'cusco-monasterio']) }}"
                            class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="barcelona espana españa"
                         data-tipo="resort">
                    <img
                        src="{{ asset('icons/HotelArtsBarcelona.jpg') }}"
                        alt="Hotel Arts Barcelona"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">RESORT URBANO</span>
                        <h3>Hotel Arts Barcelona</h3>
                        <p class="hotel-ubicacion">Barcelona, España (Port Olímpic)</p>
                        <p class="hotel-servicios">
                            2 estrellas Michelin · Spa panorámico · Playa
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 990</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 3,474.90</small>
                            </div>

                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'barcelona-arts']) }}"
                            class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="lima peru perú miraflores"
                         data-tipo="hotel">
                    <img
                        src="{{ asset('icons/MirafloresParkLimaJuniorSuite.jpg') }}"
                        alt="Miraflores Park, Lima"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">HOTEL DE LUJO</span>
                        <h3>Miraflores Park, Lima</h3>
                        <p class="hotel-ubicacion">Lima, Perú (Malecón)</p>
                        <p class="hotel-servicios">
                            Piscina en azotea · Vista al mar · Tragaluz
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 265</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 930.15</small>
                            </div>

                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'lima-miraflores']) }}"
                            class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="cusco peru perú"
                         data-tipo="hotel">
                    <img
                        src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=900"
                        alt="Hotel con piscina"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">HOTEL</span>
                        <h3>Hotel Imperial Cusco</h3>
                        <p class="hotel-ubicacion">Cusco, Perú</p>
                        <p class="hotel-servicios">
                            Wi-Fi · Desayuno · Piscina
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 71</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 250.00</small>
                            </div>

                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'cusco']) }}"
                            class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="lima peru perú"
                         data-tipo="resort">
                    <img
                        src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=900"
                        alt="Alojamiento con piscina"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">RESORT</span>
                        <h3>Ocean Resort Lima</h3>
                        <p class="hotel-ubicacion">Lima, Perú</p>
                        <p class="hotel-servicios">
                            Piscina · Restaurante · Wi-Fi
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 91</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 320.00</small>
                            </div>
                             <a href="{{ route('alojamientos.detalle', ['codigo' => 'lima']) }}"
                             class="hotel-ver">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="arequipa peru perú"
                         data-tipo="hostal">
                    <img
                        src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=900"
                        alt="Fachada de alojamiento turístico"
                        loading="lazy">

                    <div class="hotel-info">
                        <span class="hotel-tipo">HOSTAL</span>
                        <h3>Hostal Mirador Arequipa</h3>
                        <p class="hotel-ubicacion">Arequipa, Perú</p>
                        <p class="hotel-servicios">
                            Wi-Fi · Terraza · Desayuno
                        </p>

                        <div class="hotel-footer">
                            <div>
                                <small>Precio referencial / noche</small>
                                <strong>US$ 34</strong>
                                <small style="display:block; font-size:11px; color:#64748b; font-weight:500;">aprox. S/ 120.00</small>
                            </div>
                            <a href="{{ route('alojamientos.detalle', ['codigo' => 'arequipa']) }}"
                             class="hotel-ver">
                             Ver detalles
                            </a>
                        </div>
                    </div>
                </article>

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
            const tipo = document.getElementById('tipoHotel');
            const tarjetas = [...document.querySelectorAll('.hotel-card')];
            const contador = document.getElementById('hotelContador');
            const vacio = document.getElementById('hotelVacio');

            function filtrar() {
                const ciudad = (destino ? destino.value : '').trim().toLowerCase();
                const categoria = tipo ? tipo.value : 'todos';
                let visibles = 0;

                tarjetas.forEach(tarjeta => {
                    const coincideCiudad =
                        !ciudad || (tarjeta.dataset.destino || '').toLowerCase().includes(ciudad);

                    const coincideTipo =
                        categoria === 'todos' ||
                        tarjeta.dataset.tipo === categoria;

                    const mostrar = coincideCiudad && coincideTipo;
                    tarjeta.hidden = !mostrar;

                    if (mostrar) visibles++;
                });

                if (contador) {
                    contador.textContent =
                        visibles + ' alojamientos encontrados (demostración)';
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

            if (tipo) {
                tipo.addEventListener('change', filtrar);
            }
        });
    </script>
</x-layout>
