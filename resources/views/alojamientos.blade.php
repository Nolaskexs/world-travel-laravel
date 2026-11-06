
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

        <section class="hotel-container">
            <form class="hotel-buscador" id="formHoteles">
                <div class="hotel-campo">
                    <label for="hotelDestino">Destino</label>
                    <input type="search" id="hotelDestino"
                           placeholder="¿A dónde viajas?">
                </div>

                <div class="hotel-campo">
                    <label for="hotelEntrada">Entrada</label>
                    <input type="date" id="hotelEntrada">
                </div>

                <div class="hotel-campo">
                    <label for="hotelSalida">Salida</label>
                    <input type="date" id="hotelSalida">
                </div>

                <div class="hotel-campo">
                    <label for="hotelHuespedes">Huéspedes</label>
                    <select id="hotelHuespedes">
                        <option value="1">1 huésped</option>
                        <option value="2" selected>2 huéspedes</option>
                        <option value="3">3 huéspedes</option>
                        <option value="4">4 huéspedes</option>
                    </select>
                </div>

                <button type="submit" class="hotel-boton">
                    Buscar
                </button>
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
                3 alojamientos disponibles en esta demostración
            </p>

            <div class="hotel-grid" id="listaHoteles">

                <article class="hotel-card"
                         data-destino="cusco"
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
                                <strong>S/ 250</strong>
                            </div>
                            <button type="button" class="hotel-ver">
                                Ver detalles
                            </button>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="lima"
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
                                <strong>S/ 320</strong>
                            </div>
                            <button type="button" class="hotel-ver">
                                Ver detalles
                            </button>
                        </div>
                    </div>
                </article>

                <article class="hotel-card"
                         data-destino="arequipa"
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
                                <strong>S/ 120</strong>
                            </div>
                            <button type="button" class="hotel-ver">
                                Ver detalles
                            </button>
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

        .hotel-buscador {
            background: white;
            border-radius: 16px;
            padding: 22px;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr auto;
            gap: 12px;
            align-items: end;
            margin-top: -48px;
            position: relative;
            box-shadow: 0 9px 28px rgba(15, 30, 45, .1);
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
            const destino = document.getElementById('hotelDestino');
            const tipo = document.getElementById('tipoHotel');
            const entrada = document.getElementById('hotelEntrada');
            const salida = document.getElementById('hotelSalida');
            const tarjetas = [...document.querySelectorAll('.hotel-card')];
            const contador = document.getElementById('hotelContador');
            const vacio = document.getElementById('hotelVacio');

            const hoy = new Date();
            const fechaLocal = new Date(
                hoy.getTime() - hoy.getTimezoneOffset() * 60000
            ).toISOString().slice(0, 10);

            entrada.min = fechaLocal;
            salida.min = fechaLocal;

            entrada.addEventListener('change', () => {
                salida.min = entrada.value || fechaLocal;
                if (salida.value && salida.value < salida.min) {
                    salida.value = '';
                }
            });

            function filtrar() {
                const ciudad = destino.value.trim().toLowerCase();
                const categoria = tipo.value;
                let visibles = 0;

                tarjetas.forEach(tarjeta => {
                    const coincideCiudad =
                        tarjeta.dataset.destino.includes(ciudad);

                    const coincideTipo =
                        categoria === 'todos' ||
                        tarjeta.dataset.tipo === categoria;

                    const mostrar = coincideCiudad && coincideTipo;
                    tarjeta.hidden = !mostrar;

                    if (mostrar) visibles++;
                });

                contador.textContent =
                    visibles + ' alojamientos encontrados (demostración)';

                vacio.hidden = visibles !== 0;
            }

            form.addEventListener('submit', (evento) => {
                evento.preventDefault();
                if (!entrada.value && salida.value) {
                    entrada.reportValidity();
                    return;
                }
                filtrar();
            });

            tipo.addEventListener('change', filtrar);

            document.querySelectorAll('.hotel-ver').forEach(boton => {
                boton.addEventListener('click', () => {
                    alert('Próximamente: detalles y reservas del alojamiento.');
                });
            });
        });
    </script>
</x-layout>
