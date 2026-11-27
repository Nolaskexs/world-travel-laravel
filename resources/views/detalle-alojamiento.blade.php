
<x-layout>
    <x-slot:titulo>
        World Travel | Detalles del alojamiento
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    @php
        $img = $alojamiento->imagen_tarjeta ?: $alojamiento->imagen ?: 'icons/hotel1.jpg';
        $imgHotel = str_starts_with($img, 'http') ? $img : asset(ltrim($img, '/'));

        $hotel = [
            'codigo' => $alojamiento->codigo,
            'nombre' => $alojamiento->nombre,
            'tipo' => $alojamiento->tipo,
            'ubicacion' => $alojamiento->ubicacion,
            'precio' => (float) $alojamiento->precio,
            'precio_anterior' => (float) $alojamiento->precio_anterior,
            'moneda_simbolo' => $alojamiento->moneda_simbolo ?? 'US$',
            'puntuacion' => $alojamiento->puntuacion,
            'estrellas' => $alojamiento->estrellas,
            'habitacion' => $alojamiento->habitacion,
            'estadia' => $alojamiento->categoria,
            'imagen' => $imgHotel,
            'descripcion' => $alojamiento->descripcion,
            'servicios' => is_array($alojamiento->servicios) ? $alojamiento->servicios : [],
        ];
    @endphp
<div class="detalle-alojamiento">
        <div class="detalle-contenedor">

            <a class="detalle-volver" href="{{ route('alojamientos') }}">
                &larr; Volver a alojamientos
            </a>

            @if($hotel)
                <h1>{{ $hotel['nombre'] }}</h1>
                <p class="detalle-ubicacion">
                    <span>{{ $hotel['ubicacion'] }}</span>
                    @if(!empty($hotel['habitacion'])) · <span>{{ $hotel['habitacion'] }}</span> @endif
                    @if(!empty($hotel['puntuacion'])) · <span>Puntuación: {{ $hotel['puntuacion'] }}/5</span> @endif
                </p>

                <img class="detalle-imagen"
                     src="{{ $imgHotel }}"
                     alt="{{ $hotel['nombre'] }}"
                     onerror="this.onerror=null; this.src='{{ asset('icons/hotel1.jpg') }}';">

                <div class="detalle-columnas">
                    
                <section class="detalle-info">

                    <h2>Acerca de este alojamiento</h2>
                    <p>{{ $hotel['descripcion'] }}</p>

                    <div class="detalle-seccion">
                        <h2>Servicios disponibles</h2>

                        <div class="detalle-servicios">
                            @foreach($hotel['servicios'] as $servicio)
                                <span>{{ $servicio }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="detalle-seccion">
                        <h2>Información del alojamiento</h2>

                        <div class="detalle-caracteristicas">
                            <div>
                                <strong>Check-in</strong>
                                <p>Desde las 3:00 p. m.</p>
                            </div>

                            <div>
                                <strong>Check-out</strong>
                                <p>Hasta las 12:00 p. m.</p>
                            </div>

                            <div>
                                <strong>Capacidad</strong>
                                <p>De 1 a 4 huéspedes</p>
                            </div>

                            <div>
                                <strong>Tipo de alojamiento</strong>
                                <p>{{ $hotel['tipo'] ?? 'Hotel' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="detalle-seccion">
                        <h2>Políticas del alojamiento</h2>

                        <p>
                            Los horarios, servicios y condiciones mostrados
                            son referenciales para esta demostración.
                            La disponibilidad y las políticas definitivas
                            se confirmarán al integrar el sistema de reservas.
                        </p>
                    </div>

                </section>


                   
                    <aside class="detalle-reserva">
                        <h2>Reserva tu alojamiento</h2>

                        <p>Precio referencial por noche</p>
                        @if(($hotel['moneda_simbolo'] ?? 'US$') === 'S/')
                            <strong>S/ {{ number_format($hotel['precio'], 0, '.', ',') }}</strong>
                            <small style="display:block; font-size:12px; color:#64748b; font-weight:600; margin-top:-10px; margin-bottom:12px;">aprox. US$ {{ number_format($hotel['precio'] / 3.51, 2) }}</small>
                        @else
                            <strong>US$ {{ number_format($hotel['precio'], 0, '.', ',') }}</strong>
                            <small style="display:block; font-size:12px; color:#64748b; font-weight:600; margin-top:-10px; margin-bottom:12px;">aprox. S/ {{ number_format($hotel['precio'] * 3.51, 2) }}</small>
                        @endif
                        @if(!empty($hotel['precio_anterior']))
                            <small style="text-decoration: line-through; color: #94a3b8; display: block; margin-top: -15px; margin-bottom: 15px;">
                                Precio habitual: {{ $hotel['moneda_simbolo'] ?? 'US$' }} {{ number_format($hotel['precio_anterior'], 0, '.', ',') }}
                            </small>
                        @endif

                        <form id="formReservaHotel" data-precio="{{ $hotel['precio'] }}" data-simbolo="{{ $hotel['moneda_simbolo'] ?? 'US$' }}">

                            <div class="reserva-campo">
                                <label for="fechaEntrada">Fecha de entrada</label>
                                <input type="date" id="fechaEntrada" required>
                            </div>

                            <div class="reserva-campo">
                                <label for="fechaSalida">Fecha de salida</label>
                                <input type="date" id="fechaSalida" required>
                            </div>

                            <div class="reserva-campo">
                                <label for="cantidadHuespedes">Huéspedes</label>
                                <select id="cantidadHuespedes" required>
                                    <option value="1">1 huésped</option>
                                    <option value="2" selected>2 huéspedes</option>
                                    <option value="3">3 huéspedes</option>
                                    <option value="4">4 huéspedes</option>
                                </select>
                            </div>

                            <div class="reserva-resumen">
                                <p>Noches: <span id="totalNoches">0</span></p>
                                <p>Total estimado:</p>
                                <h3 id="precioTotal">{{ $hotel['moneda_simbolo'] ?? 'US$' }} 0.00</h3>
                                <small id="precioSecundario" style="display:block; font-size:12px; color:#64748b; font-weight:600; margin-top:-4px;">aprox. {{ ($hotel['moneda_simbolo'] ?? 'US$') === 'S/' ? 'US$ 0.00' : 'S/ 0.00' }}</small>
                            </div>

                            <button type="submit">
                                Continuar con la reserva
                            </button>
                            @guest
                                <small style="display: block; text-align: center; color: #64748b; margin-top: 6px; font-size: 0.78rem;">Requiere iniciar sesión para reservar</small>
                            @endguest

                            <small>
                                No se realizará ningún cobro. La reserva quedará registrada en la base de datos.
                            </small>
                        </form>
                    </aside>

                </div>
            @else
                <h1>Alojamiento no encontrado</h1>
                <p>El alojamiento solicitado no está disponible.</p>
            @endif

        </div>
    </div>

    <style>
        .detalle-alojamiento {
            background: #f3f5f8;
            color: #102331;
            min-height: 75vh;
            font-family: 'Inter', sans-serif;
        }

        .detalle-alojamiento * {
            box-sizing: border-box;
        }

        .detalle-contenedor {
            max-width: 1120px;
            margin: auto;
            padding: 40px 20px 70px;
        }

        .detalle-volver {
            display: inline-block;
            margin-bottom: 25px;
            text-decoration: none;
            color: #236487;
            font-weight: 600;
        }

        .detalle-contenedor h1 {
            font-size: 34px;
            margin-bottom: 10px;
        }

        .detalle-ubicacion {
            color: #647482;
            margin-bottom: 25px;
        }

        .detalle-imagen {
            width: 100%;
            height: 390px;
            object-fit: cover;
            border-radius: 15px;
        }

        .detalle-columnas {
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            gap: 25px;
            margin-top: 30px;
        }

        .detalle-info,
        .detalle-reserva {
            background: white;
            padding: 28px;
            border-radius: 14px;
            border: 1px solid #e4e9ee;
        }

        .detalle-info h2 {
            font-size: 21px;
            margin-bottom: 15px;
        }

        .detalle-info p {
            line-height: 1.8;
            color: #637484;
            margin-bottom: 30px;
        }

        .detalle-servicios {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .detalle-servicios span {
            background: #edf5f9;
            color: #164864;
            padding: 10px 15px;
            border-radius: 8px;
        }

        .detalle-reserva strong {
            display: block;
            font-size: 32px;
            margin: 20px 0;
        }

        .detalle-reserva button {
            width: 100%;
            background: #0b2535;
            color: white;
            border: 0;
            border-radius: 8px;
            padding: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .detalle-reserva button:hover {
            background: #205979;
        }

        .detalle-reserva small {
            display: block;
            margin-top: 15px;
            color: #72808a;
        }

        @media (max-width: 700px) {
            .detalle-columnas {
                grid-template-columns: 1fr;
            }

            .detalle-imagen {
                height: 250px;
            }
        }

                .reserva-campo {
            margin: 16px 0;
        }

        .reserva-campo label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .reserva-campo input,
        .reserva-campo select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce2e8;
            border-radius: 8px;
            background: white;
            color: #102331;
            font: inherit;
        }

        .reserva-resumen {
            margin: 20px 0;
            padding: 18px;
            background: #f3f7fa;
            border-radius: 10px;
        }

        .reserva-resumen p {
            margin: 5px 0;
            font-size: 13px;
        }

        .reserva-resumen h3 {
            font-size: 25px;
            color: #0b2535;
            margin-top: 10px;
        }

        .detalle-seccion {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #e7edf2;
        }

        .detalle-caracteristicas {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .detalle-caracteristicas > div {
            background: #f5f8fa;
            border: 1px solid #edf0f3;
            border-radius: 10px;
            padding: 18px;
        }

        .detalle-caracteristicas strong {
            color: #102331;
            font-size: 14px;
        }

        .detalle-caracteristicas p {
            color: #647482;
            font-size: 13px;
            margin: 8px 0 0;
            line-height: 1.5;
        }

        @media (max-width: 480px) {
            .detalle-caracteristicas {
                grid-template-columns: 1fr;
            }
        }
    </style>

  

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const formulario = document.getElementById('formReservaHotel');

        if (!formulario) return;

        const entrada = document.getElementById('fechaEntrada');
        const salida = document.getElementById('fechaSalida');
        const huespedes = document.getElementById('cantidadHuespedes');
        const nochesTexto = document.getElementById('totalNoches');
        const totalTexto = document.getElementById('precioTotal');

        const precioNoche = Number(formulario.dataset.precio);

        // Obtener fecha actual en horario local.
        const hoy = new Date();
        const fechaLocal = new Date(
            hoy.getTime() - hoy.getTimezoneOffset() * 60000
        ).toISOString().slice(0, 10);

        entrada.min = fechaLocal;
        salida.min = fechaLocal;

        const simbolo = formulario.dataset.simbolo || 'US$';

        function calcularTotal() {
            if (!entrada.value || !salida.value) {
                nochesTexto.textContent = '0';
                totalTexto.textContent = simbolo + ' 0.00';
                return;
            }

            const inicio = Date.parse(entrada.value + 'T00:00:00Z');
            const fin = Date.parse(salida.value + 'T00:00:00Z');

            const noches = Math.round((fin - inicio) / 86400000);

            if (noches <= 0) {
                nochesTexto.textContent = '0';
                totalTexto.textContent = simbolo + ' 0.00';
                const secEl = document.getElementById('precioSecundario');
                if (secEl) {
                    secEl.textContent = 'aprox. ' + (simbolo === 'S/' ? 'US$ 0.00' : 'S/ 0.00');
                }
                return;
            }

            const total = noches * precioNoche;
            nochesTexto.textContent = noches;
            totalTexto.textContent =
                simbolo + ' ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const secEl = document.getElementById('precioSecundario');
            if (secEl) {
                if (simbolo === 'S/') {
                    secEl.textContent = 'aprox. US$ ' + (total / 3.51).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                } else {
                    secEl.textContent = 'aprox. S/ ' + (total * 3.51).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }

        entrada.addEventListener('change', () => {
            if (entrada.value) {
                const siguiente = new Date(entrada.value + 'T00:00:00Z');
                siguiente.setUTCDate(siguiente.getUTCDate() + 1);

                salida.min = siguiente.toISOString().slice(0, 10);

                if (salida.value && salida.value < salida.min) {
                    salida.value = '';
                }
            } else {
                salida.min = fechaLocal;
            }

            calcularTotal();
        });

        salida.addEventListener('change', calcularTotal);

        formulario.addEventListener('submit', (evento) => {
            evento.preventDefault();

            calcularTotal();

            if (!formulario.reportValidity()) return;

            if (!entrada.value || !salida.value ||
                salida.value <= entrada.value) {
                alert('Selecciona fechas válidas.');
                return;
            }

            const inicio = Date.parse(entrada.value + 'T00:00:00Z');
            const fin = Date.parse(salida.value + 'T00:00:00Z');
            const noches = Math.max(1, Math.round((fin - inicio) / 86400000));

            const parametros = new URLSearchParams({
                alojamiento: @json($alojamiento->codigo),
                entrada: entrada.value,
                salida: salida.value,
                huespedes: huespedes.value,
                noches: noches,
                total: (noches * precioNoche).toFixed(2),
                moneda: simbolo
            });

            const url = @json(route('reservas.confirmar.alojamientos'));

            window.location.href =
                url + '?' + parametros.toString();
        });
    });
    </script>
</x-layout>