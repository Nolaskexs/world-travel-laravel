
<x-layout>
    <x-slot:titulo>
        World Travel | Detalles del alojamiento
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    @php
        $hoteles = [
            'tokio-ritz' => [
                'nombre' => 'The Ritz-Carlton, Tokyo',
                'tipo' => 'Hotel de Lujo',
                'ubicacion' => 'Tokio, Japón (Roppongi)',
                'precio' => 19957,
                'precio_anterior' => 20032,
                'moneda_simbolo' => 'US$',
                'puntuacion' => 4.3,
                'estrellas' => 5,
                'habitacion' => 'Deluxe Room, 2 Queen Beds',
                'estadia' => '18 noches, 2 personas',
                'imagen' => 'icons/hotel1.jpg',
                'descripcion' => 'Ubicado en los pisos más altos de la icónica torre Midtown en Roppongi, The Ritz-Carlton, Tokyo ofrece impresionantes vistas panorámicas del Monte Fuji y de la bahía de Tokio. Disfruta de gastronomía con estrellas Michelin, un spa de nivel internacional y un servicio legendario en el corazón cosmopolita de Japón.',
                'servicios' => ['Wi-Fi de alta velocidad', 'Spa & Wellness de lujo', 'Piscina cubierta climatizada', 'Restaurantes con estrella Michelin', 'Servicio de conserjería 24/7', 'Gimnasio con vista panorámica', 'Desayuno buffet internacional']
            ],
            'cartagena-oz' => [
                'nombre' => 'Oz Hotel Luxury',
                'tipo' => 'Hotel Boutique',
                'ubicacion' => 'Cartagena de Indias, Colombia (Bocagrande)',
                'precio' => 74,
                'precio_anterior' => 104,
                'moneda_simbolo' => 'US$',
                'puntuacion' => 4.8,
                'estrellas' => 5,
                'habitacion' => 'Classic Room',
                'estadia' => '1 noche, 2 personas',
                'imagen' => 'icons/hotel3.jpg',
                'descripcion' => 'Situado en el corazón turístico de Bocagrande a dos calles de las playas del Caribe, Oz Hotel Luxury fusiona confort contemporáneo, hospitalidad caribeña y un espectacular rooftop lounge con vistas panorámicas a la bahía y a la ciudad colonial amurallada.',
                'servicios' => ['Desayuno buffet incluido', 'Wi-Fi gratuito', 'Rooftop bar panorámico', 'Aire acondicionado', 'Recepción y seguridad 24h', 'Servicio a la habitación']
            ],
            'madrid-riu' => [
                'nombre' => 'Hotel Riu Plaza España',
                'tipo' => 'Hotel',
                'ubicacion' => 'Madrid, España (Gran Vía)',
                'precio' => 268,
                'precio_anterior' => 414,
                'moneda_simbolo' => 'US$',
                'puntuacion' => 4.9,
                'estrellas' => 5,
                'habitacion' => 'Habitación Doble de Lujo',
                'estadia' => '1 noche, 2 personas',
                'imagen' => 'icons/hotel2.jpg',
                'descripcion' => 'Emplazado en el histórico rascacielos Edificio España en plena Gran Vía madrileña. Célebre por su pasarela de cristal y su Sky Bar 360° en la planta 27, ofrece habitaciones de diseño sofisticado y acceso directo a los mejores teatros, tiendas y museos de la capital.',
                'servicios' => ['Sky Bar 360° en piso 27', 'Desayuno buffet gourmet', 'Piscina exterior de temporada', 'Gimnasio 24 horas', 'Wi-Fi de cortesía', 'Restaurante gastronómico']
            ],
            'cusco-monasterio' => [
                'nombre' => 'Belmond Monasterio, Cusco',
                'tipo' => 'Hotel Histórico de Lujo',
                'ubicacion' => 'Cusco, Perú (Plazoleta Nazarenas)',
                'precio' => 540,
                'precio_anterior' => 620,
                'moneda_simbolo' => 'US$',
                'puntuacion' => 4.7,
                'estrellas' => 5,
                'habitacion' => 'Habitación Superior',
                'estadia' => '2 noches, 2 personas',
                'imagen' => 'icons/belmond-hotel-monasterio.jpg',
                'descripcion' => 'Antiguo monasterio renacentista fundado en 1592, transformado en un exclusivo santuario de hospitalidad cusqueña. Cuenta con claustros coloniales empedrados, arte virreinal original, alta gastronomía andina y habitaciones enriquecidas con oxígeno.',
                'servicios' => ['Enriquecimiento de oxígeno en habitación', 'Desayuno a la carta en el patio', 'Tours privados de arte colonial', 'Restaurante Illariy', 'Lobby bar de pisco y cava', 'Wi-Fi en toda la propiedad']
            ],
            'barcelona-arts' => [
                'nombre' => 'Hotel Arts Barcelona',
                'tipo' => 'Resort Urbano de Lujo',
                'ubicacion' => 'Barcelona, España (Port Olímpic)',
                'precio' => 990,
                'precio_anterior' => 1150,
                'moneda_simbolo' => 'US$',
                'puntuacion' => 4.6,
                'estrellas' => 5,
                'habitacion' => 'Deluxe Vista al Mar',
                'estadia' => '3 noches, 2 personas',
                'imagen' => 'icons/HotelArtsBarcelona.jpg',
                'descripcion' => 'Imponente rascacielos de cristal y acero a orillas del mar Mediterráneo junto al puerto olímpico de Barcelona. Alberga el célebre restaurante Enoteca Paco Pérez galardonado con 2 estrellas Michelin, jardines mediterráneos y piscinas panorámicas frente a la playa.',
                'servicios' => ['Restaurante 2 estrellas Michelin', '43 The Spa con vistas al mar', 'Piscinas infinitas exteriores', 'Acceso directo a la playa', 'Servicio de mayordomo', 'Wi-Fi de alta velocidad']
            ],
            'lima-miraflores' => [
                'nombre' => 'Miraflores Park, Lima',
                'tipo' => 'Hotel de Lujo',
                'ubicacion' => 'Lima, Perú (Malecón de la Reserva)',
                'precio' => 265,
                'precio_anterior' => 330,
                'moneda_simbolo' => 'US$',
                'puntuacion' => 4.8,
                'estrellas' => 5,
                'habitacion' => 'Junior Suite',
                'estadia' => '1 noche, 2 personas',
                'imagen' => 'icons/MirafloresParkLimaJuniorSuite.jpg',
                'descripcion' => 'Elegante hotel boutique sobre los acantilados de Miraflores con vistas incomparables al Océano Pacífico. Cuenta con piscina climatizada en la azotea, spa botánico Zest y el prestigioso restaurante Tragaluz.',
                'servicios' => ['Piscina infinity en azotea', 'Restaurante de autor Tragaluz', 'Zest Spa botánico', 'Bicicletas de cortesía', 'Desayuno buffet en piso 11', 'Wi-Fi de cortesía']
            ],
            'cusco' => [
                'nombre' => 'Hotel Imperial Cusco',
                'tipo' => 'Hotel',
                'ubicacion' => 'Cusco, Perú',
                'precio' => 250,
                'precio_anterior' => null,
                'moneda_simbolo' => 'S/',
                'puntuacion' => 4.5,
                'estrellas' => 4,
                'habitacion' => 'Habitación Estándar',
                'estadia' => '1 noche, 2 personas',
                'imagen' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200',
                'descripcion' => 'Disfruta de una estadía cómoda en Cusco, ideal para descansar después de explorar la ciudad histórica, el Valle Sagrado y Machu Picchu.',
                'servicios' => ['Wi-Fi gratuito', 'Desayuno buffet andino', 'Piscina temperada', 'Calefacción central', 'Custodia de equipaje']
            ],
            'lima' => [
                'nombre' => 'Ocean Resort Lima',
                'tipo' => 'Resort',
                'ubicacion' => 'Lima, Perú',
                'precio' => 320,
                'precio_anterior' => null,
                'moneda_simbolo' => 'S/',
                'puntuacion' => 4.6,
                'estrellas' => 4,
                'habitacion' => 'Habitación Ejecutiva',
                'estadia' => '1 noche, 2 personas',
                'imagen' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200',
                'descripcion' => 'Un resort costero frente al mar para disfrutar de una estadía relajante con gastronomía peruana marina, piscinas y amplias áreas verdes.',
                'servicios' => ['Piscina', 'Restaurante marino', 'Wi-Fi', 'Gimnasio', 'Estacionamiento']
            ],
            'arequipa' => [
                'nombre' => 'Hostal Mirador Arequipa',
                'tipo' => 'Hostal',
                'ubicacion' => 'Arequipa, Perú',
                'precio' => 120,
                'precio_anterior' => null,
                'moneda_simbolo' => 'S/',
                'puntuacion' => 4.4,
                'estrellas' => 3,
                'habitacion' => 'Habitación Tradicional',
                'estadia' => '1 noche, 2 personas',
                'imagen' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200',
                'descripcion' => 'Una opción accesible, acogedora y con vista a los volcanes Misti y Chachani, perfecta para descubrir la Ciudad Blanca y el Cañón del Colca.',
                'servicios' => ['Wi-Fi', 'Terraza con vista a volcanes', 'Desayuno típico arequipeño', 'Información turística']
            ]
        ];

        $hotel = $hoteles[$codigo] ?? null;
        $imgHotel = $hotel ? (str_starts_with($hotel['imagen'], 'http') ? $hotel['imagen'] : asset($hotel['imagen'])) : null;
    @endphp

    <div class="detalle-alojamiento">
        <div class="detalle-contenedor">

            <a class="detalle-volver" href="{{ route('alojamientos') }}">
                &larr; Volver a alojamientos
            </a>

            @if($hotel)
                <h1>{{ $hotel['nombre'] }}</h1>
                <p class="detalle-ubicacion">
                    <span>📍 {{ $hotel['ubicacion'] }}</span>
                    @if(!empty($hotel['habitacion'])) · <span>🛏️ {{ $hotel['habitacion'] }}</span> @endif
                    @if(!empty($hotel['puntuacion'])) · <span>⭐ {{ $hotel['puntuacion'] }}/5</span> @endif
                </p>

                <img class="detalle-imagen"
                     src="{{ $imgHotel }}"
                     alt="{{ $hotel['nombre'] }}">

                <div class="detalle-columnas">
                    
                <section class="detalle-info">

                    <h2>Acerca de este alojamiento</h2>
                    <p>{{ $hotel['descripcion'] }}</p>

                    <div class="detalle-seccion">
                        <h2>Servicios disponibles</h2>

                        <div class="detalle-servicios">
                            @foreach($hotel['servicios'] as $servicio)
                                <span>✓ {{ $servicio }}</span>
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
                        <strong>{{ $hotel['moneda_simbolo'] ?? 'US$' }} {{ number_format($hotel['precio'], 0, '.', ',') }}</strong>
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
                            </div>

                            <button type="submit">
                                Continuar con la reserva
                            </button>

                            <small>
                                Demostración: no se realizará ningún cobro
                                ni reserva real.
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
                return;
            }

            nochesTexto.textContent = noches;
            totalTexto.textContent =
                simbolo + ' ' + (noches * precioNoche).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
                hotel: @json($codigo),
                hotel_nombre: @json($hotel['nombre'] ?? ''),
                hotel_ubicacion: @json($hotel['ubicacion'] ?? ''),
                entrada: entrada.value,
                salida: salida.value,
                huespedes: huespedes.value,
                noches: noches,
                total: (noches * precioNoche).toFixed(2),
                moneda: simbolo
            });

            const url = @json(route('reservas.confirmar'));

            window.location.href =
                url + '?' + parametros.toString();
        });
    });
    </script>
</x-layout>