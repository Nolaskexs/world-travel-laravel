
<x-layout>
    <x-slot:titulo>
        World Travel | Confirmar reserva
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <main class="wt-checkout">
        <div class="checkout-container">

            <a href="{{ route('alojamientos') }}" class="checkout-volver">
                &larr; Volver a alojamientos
            </a>

            <div class="checkout-heading" id="checkoutHeading">
                <span class="checkout-eyebrow">WORLD TRAVEL / RESERVAS</span>
                <h1>Confirma tu alojamiento</h1>
                <p>Ya casi terminas. Revisa tu estadía y completa tus datos.</p>
            </div>

            <div class="checkout-pasos" id="checkoutPasos">
                <span class="paso-listo">1. Alojamiento</span>
                <span class="paso-linea"></span>
                <span class="paso-listo">2. Fechas</span>
                <span class="paso-linea"></span>
                <span class="paso-actual">3. Confirmación</span>
            </div>

            <div class="checkout-grid" id="checkoutContenido">

                <!-- INFORMACIÓN DEL VIAJERO -->
                <section class="checkout-card">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">01</span>
                        <div>
                            <h2>Información del viajero</h2>
                            <p>Completa tus datos para continuar.</p>
                        </div>
                    </div>

                    <form id="formViajero">

                        <div class="checkout-campo">
                            <label for="nombreViajero">Nombre completo</label>
                            <input type="text" id="nombreViajero"
                                placeholder="Ej. Juan Pérez"
                                minlength="3"
                                maxlength="100"
                                autocomplete="name"
                                required>
                        </div>

                        <div class="checkout-campo">
                            <label for="correoViajero">Correo electrónico</label>
                            <input type="email" id="correoViajero"
                                placeholder="correo@ejemplo.com"
                                autocomplete="email"
                                required>
                        </div>

                        <div class="checkout-campo">
                            <label for="telefonoViajero">Número de teléfono</label>
                            <input type="tel" id="telefonoViajero"
                                placeholder="Ej. 987654321"
                                minlength="7"
                                maxlength="20"
                                autocomplete="tel"
                                required>
                        </div>

                        <div class="checkout-campo">
                            <label for="comentariosViajero">
                                Solicitudes especiales
                                <span class="opcional">(Opcional)</span>
                            </label>
                            <textarea id="comentariosViajero"
                                rows="4"
                                maxlength="500"
                                placeholder="Cuéntanos si necesitas algo especial..."></textarea>
                        </div>

                        <div class="checkout-nota">
                            Esta es una demostración académica.
                            Los datos no se guardarán ni se enviarán al servidor.
                        </div>

                        <button type="submit" class="checkout-submit">
                            Revisar solicitud de reserva
                            <span aria-hidden="true">&rarr;</span>
                        </button>
                    </form>
                </section>

                <!-- RESUMEN DEL HOTEL -->
                <aside class="checkout-card checkout-resumen">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">02</span>
                        <div>
                            <h2>Resumen de tu viaje</h2>
                            <p>Información de tu estadía</p>
                        </div>
                    </div>

                    <img id="imagenHotel"
                         class="checkout-imagen"
                         alt="Fotografía del alojamiento">

                    <h3 id="hotelNombre">Cargando alojamiento...</h3>
                    <p id="hotelUbicacion" class="checkout-ubicacion"></p>

                    <div class="checkout-fechas">
                        <div>
                            <span>Entrada</span>
                            <strong id="hotelEntrada">-</strong>
                        </div>
                        <div>
                            <span>Salida</span>
                            <strong id="hotelSalida">-</strong>
                        </div>
                    </div>

                    <div class="checkout-fila">
                        <span>Huéspedes</span>
                        <strong id="hotelHuespedes">-</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Duración</span>
                        <strong><span id="hotelNoches">-</span> noches</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Precio por noche</span>
                        <strong id="hotelPrecio">S/ 0.00</strong>
                    </div>

                    <div class="checkout-total">
                        <span>Total estimado</span>
                        <strong id="hotelTotal">S/ 0.00</strong>
                    </div>

                    <p class="checkout-aclaracion">
                        Precio referencial. No se ha realizado ningún pago.
                    </p>
                </aside>
            </div>

            <!-- RESULTADO EN LA MISMA PÁGINA -->
            <section id="resultadoReserva"
                     class="checkout-resultado"
                     hidden
                     aria-live="polite">

                <div class="resultado-icono" aria-hidden="true">
                    &#10003;
                </div>

                <span class="checkout-eyebrow">WORLD TRAVEL</span>

                <h2>Datos de la solicitud verificados</h2>

                <p class="resultado-descripcion">
                    Has completado correctamente el formulario
                    de demostración de tu alojamiento.
                </p>

                <div class="resultado-detalles">
                    <div>
                        <span>Viajero</span>
                        <strong id="resultadoNombre">-</strong>
                    </div>

                    <div>
                        <span>Alojamiento</span>
                        <strong id="resultadoHotel">-</strong>
                    </div>

                    <div>
                        <span>Fechas</span>
                        <strong id="resultadoFechas">-</strong>
                    </div>

                    <div>
                        <span>Huéspedes</span>
                        <strong id="resultadoHuespedes">-</strong>
                    </div>

                    <div class="resultado-precio">
                        <span>Total estimado</span>
                        <strong id="resultadoTotal">S/ 0.00</strong>
                    </div>
                </div>

                <p class="resultado-advertencia">
                    Esta solicitud no ha sido registrada en la base de datos.
                    No constituye una reserva confirmada.
                </p>

                <a href="{{ route('alojamientos') }}"
                   class="resultado-boton">
                    Explorar más alojamientos
                </a>
            </section>

        </div>
    </main>

    <style>
        .wt-checkout {
            min-height: 85vh;
            background: #f3f6f9;
            padding: 38px 20px 90px;
            color: #102331;
            font-family: 'Inter', Arial, sans-serif;
        }

        .wt-checkout * {
            box-sizing: border-box;
        }

        .checkout-container {
            max-width: 1120px;
            margin: 0 auto;
        }

        .checkout-volver {
            color: #236487;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
        }

        .checkout-heading {
            margin: 35px 0 28px;
        }

        .checkout-eyebrow {
            color: #287096;
            letter-spacing: 2px;
            font-size: 11px;
            font-weight: 800;
        }

        .checkout-heading h1 {
            font-size: clamp(28px, 4vw, 40px);
            margin: 10px 0;
            color: #0b2535;
        }

        .checkout-heading p {
            color: #718190;
            font-size: 15px;
        }

        .checkout-pasos {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            color: #72818e;
            font-size: 13px;
            font-weight: 600;
        }

        .paso-listo {
            color: #287096;
        }

        .paso-actual {
            color: #0b2535;
        }

        .paso-linea {
            width: 42px;
            height: 1px;
            background: #cbd6df;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            align-items: start;
            gap: 25px;
        }

        .checkout-card {
            background: #fff;
            border: 1px solid #e4eaf0;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 10px 28px rgba(20, 40, 60, .04);
        }

        .checkout-card-header {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 27px;
        }

        .checkout-numero {
            display: grid;
            place-items: center;
            min-width: 39px;
            height: 39px;
            border-radius: 11px;
            color: #236487;
            background: #eaf3f8;
            font-weight: 800;
        }

        .checkout-card h2 {
            margin: 0 0 6px;
            font-size: 21px;
        }

        .checkout-card-header p {
            margin: 0;
            font-size: 13px;
            color: #778694;
        }

        .checkout-campo {
            margin-bottom: 21px;
        }

        .checkout-campo label {
            display: block;
            font-weight: 600;
            margin-bottom: 9px;
            font-size: 13px;
        }

        .opcional {
            color: #82919d;
            font-weight: 400;
        }

        .checkout-campo input,
        .checkout-campo textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #dbe3eb;
            border-radius: 9px;
            background: #fff;
            color: #102331;
            font: inherit;
            font-size: 14px;
            transition: border-color .2s;
        }

        .checkout-campo input:focus,
        .checkout-campo textarea:focus {
            outline: none;
            border-color: #287096;
            box-shadow: 0 0 0 3px rgba(40, 112, 150, .1);
        }

        .checkout-nota {
            background: #edf5fa;
            padding: 15px;
            border-radius: 10px;
            color: #426477;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 23px;
        }

        .checkout-submit {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            padding: 17px;
            border: 0;
            border-radius: 10px;
            background: #0b2535;
            color: white;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }

        .checkout-submit:hover {
            background: #205979;
        }

        .checkout-imagen {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            object-fit: cover;
            display: block;
            margin-bottom: 19px;
        }

        .checkout-resumen h3 {
            font-size: 20px;
            margin: 0 0 5px;
        }

        .checkout-ubicacion {
            color: #728190;
            font-size: 13px;
            margin: 0 0 24px;
        }

        .checkout-fechas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 17px;
        }

        .checkout-fechas > div {
            background: #f4f7fa;
            padding: 14px;
            border-radius: 10px;
        }

        .checkout-fechas span,
        .checkout-fila span,
        .checkout-total span {
            color: #6c7c89;
            font-size: 12px;
        }

        .checkout-fechas strong {
            display: block;
            margin-top: 8px;
            font-size: 14px;
        }

        .checkout-fila {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #edf0f3;
            font-size: 13px;
        }

        .checkout-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 23px 0 10px;
        }

        .checkout-total strong {
            font-size: 25px;
            color: #0b2535;
        }

        .checkout-aclaracion {
            color: #86939e;
            font-size: 12px;
            line-height: 1.6;
        }

        .checkout-resultado {
            max-width: 720px;
            background: white;
            border: 1px solid #e4eaf0;
            border-radius: 20px;
            padding: 44px;
            margin: 35px auto 0;
            text-align: center;
            box-shadow: 0 12px 30px rgba(20, 40, 60, .05);
        }

        /* Mantiene oculto el resultado hasta finalizar */
        .checkout-resultado[hidden] {
            display: none;
        }

        .resultado-icono {
            display: grid;
            place-items: center;
            background: #e3f5eb;
            color: #208452;
            width: 75px;
            height: 75px;
            border-radius: 50%;
            font-size: 38px;
            font-weight: 800;
            margin: 0 auto 22px;
        }

        .checkout-resultado h2 {
            margin: 12px 0;
            font-size: 29px;
        }

        .resultado-descripcion {
            color: #718190;
            line-height: 1.7;
        }

        .resultado-detalles {
            background: #f5f8fa;
            border-radius: 12px;
            padding: 23px;
            margin: 30px 0;
            text-align: left;
        }

        .resultado-detalles > div {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 12px 0;
            border-bottom: 1px solid #e2e9ee;
            font-size: 13px;
        }

        .resultado-detalles > div:last-child {
            border-bottom: none;
        }

        .resultado-detalles span {
            color: #738390;
        }

        .resultado-detalles strong {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .resultado-precio strong {
            color: #0b2535;
            font-size: 20px;
        }

        .resultado-advertencia {
            font-size: 12px;
            color: #7c8994;
            line-height: 1.7;
        }

        .resultado-boton {
            display: inline-block;
            background: #0b2535;
            padding: 15px 24px;
            border-radius: 9px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            margin-top: 15px;
        }

        .resultado-boton:hover {
            background: #205979;
        }

        @media (max-width: 800px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .checkout-card,
            .checkout-resultado {
                padding: 23px;
            }
        }

        @media (max-width: 480px) {
            .checkout-pasos {
                font-size: 11px;
                gap: 8px;
            }

            .paso-linea {
                width: 15px;
            }

            .checkout-fechas {
                grid-template-columns: 1fr;
            }

            .checkout-total strong {
                font-size: 21px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const datos = new URLSearchParams(window.location.search);

            const hoteles = {
                cusco: {
                    nombre: 'Hotel Imperial Cusco',
                    ubicacion: 'Cusco, Perú',
                    precio: 250,
                    imagen: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=900'
                },
                lima: {
                    nombre: 'Ocean Resort Lima',
                    ubicacion: 'Lima, Perú',
                    precio: 320,
                    imagen: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=900'
                },
                arequipa: {
                    nombre: 'Hostal Mirador Arequipa',
                    ubicacion: 'Arequipa, Perú',
                    precio: 120,
                    imagen: 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=900'
                }
            };

            const codigo = datos.get('hotel');
            const hotel = Object.hasOwn(hoteles, codigo)
                ? hoteles[codigo]
                : null;

            const entrada = datos.get('entrada');
            const salida = datos.get('salida');
            const huespedes = datos.get('huespedes');

            const formatoMoneda = (numero) =>
                'S/ ' + numero.toFixed(2);

            function fechaLegible(fecha) {
                if (!fecha || !/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
                    return '-';
                }

                const partes = fecha.split('-');
                return partes[2] + '/' + partes[1] + '/' + partes[0];
            }

            document.getElementById('hotelNombre').textContent =
                hotel ? hotel.nombre : 'No seleccionado';

            document.getElementById('hotelUbicacion').textContent =
                hotel ? hotel.ubicacion : '-';

            document.getElementById('hotelEntrada').textContent =
                fechaLegible(entrada);

            document.getElementById('hotelSalida').textContent =
                fechaLegible(salida);

            document.getElementById('hotelHuespedes').textContent =
                huespedes || '-';

            if (hotel) {
                document.getElementById('imagenHotel').src = hotel.imagen;
            }

            function obtenerFecha(valor) {
                if (!valor || !/^\d{4}-\d{2}-\d{2}$/.test(valor)) {
                    return NaN;
                }

                const tiempo = Date.parse(valor + 'T00:00:00Z');

                if (!Number.isFinite(tiempo)) return NaN;

                const fechaReal = new Date(tiempo)
                    .toISOString().slice(0, 10);

                return fechaReal === valor ? tiempo : NaN;
            }

            const inicio = obtenerFecha(entrada);
            const fin = obtenerFecha(salida);

            const noches = Number.isFinite(inicio) && Number.isFinite(fin)
                ? Math.round((fin - inicio) / 86400000)
                : 0;

            const reservaValida =
                hotel !== null &&
                Number.isInteger(noches) &&
                noches > 0 &&
                ['1', '2', '3', '4'].includes(huespedes);

            const total = reservaValida
                ? noches * hotel.precio
                : 0;

            document.getElementById('hotelNoches').textContent =
                reservaValida ? noches : '-';

            document.getElementById('hotelPrecio').textContent =
                hotel ? formatoMoneda(hotel.precio) : 'S/ 0.00';

            document.getElementById('hotelTotal').textContent =
                formatoMoneda(total);

            const formulario = document.getElementById('formViajero');

            formulario.addEventListener('submit', (evento) => {
                evento.preventDefault();

                if (!formulario.reportValidity()) return;

                if (!reservaValida) {
                    alert('Selecciona nuevamente el alojamiento y las fechas.');
                    return;
                }

                const nombre = document
                    .getElementById('nombreViajero').value.trim();

                if (nombre.length < 3) {
                    alert('Ingresa un nombre válido.');
                    return;
                }

                // Mostrar el resultado en esta misma página.
                document.getElementById('resultadoNombre').textContent =
                    nombre;

                document.getElementById('resultadoHotel').textContent =
                    hotel.nombre;

                document.getElementById('resultadoFechas').textContent =
                    fechaLegible(entrada) + ' - ' + fechaLegible(salida);

                document.getElementById('resultadoHuespedes').textContent =
                    huespedes;

                document.getElementById('resultadoTotal').textContent =
                    formatoMoneda(total);

                document.getElementById('checkoutContenido').hidden = true;
                document.getElementById('checkoutHeading').hidden = true;
                document.getElementById('checkoutPasos').hidden = true;

                const resultado =
                    document.getElementById('resultadoReserva');

                resultado.hidden = false;
                resultado.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });

        });
    </script>
</x-layout>
