
<x-layout>
    <x-slot:titulo>
        World Travel | Detalles del alojamiento
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    @php
        $hoteles = [
            'cusco' => [
                'nombre' => 'Hotel Imperial Cusco',
                'ubicacion' => 'Cusco, Perú',
                'precio' => 250,
                'imagen' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200',
                'descripcion' => 'Disfruta de una estadía cómoda en Cusco, ideal para descansar después de explorar la ciudad.',
                'servicios' => ['Wi-Fi', 'Desayuno', 'Piscina']
            ],
            'lima' => [
                'nombre' => 'Ocean Resort Lima',
                'ubicacion' => 'Lima, Perú',
                'precio' => 320,
                'imagen' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200',
                'descripcion' => 'Un alojamiento cómodo para disfrutar de una estadía relajante.',
                'servicios' => ['Piscina', 'Restaurante', 'Wi-Fi']
            ],
            'arequipa' => [
                'nombre' => 'Hostal Mirador Arequipa',
                'ubicacion' => 'Arequipa, Perú',
                'precio' => 120,
                'imagen' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200',
                'descripcion' => 'Una opción accesible para descubrir los atractivos turísticos de Arequipa.',
                'servicios' => ['Wi-Fi', 'Terraza', 'Desayuno']
            ]
        ];

        $hotel = $hoteles[$codigo] ?? null;
    @endphp

    <div class="detalle-alojamiento">
        <div class="detalle-contenedor">

            <a class="detalle-volver" href="{{ route('alojamientos') }}">
                &larr; Volver a alojamientos
            </a>

            @if($hotel)
                <h1>{{ $hotel['nombre'] }}</h1>
                <p class="detalle-ubicacion">{{ $hotel['ubicacion'] }}</p>

                <img class="detalle-imagen"
                     src="{{ $hotel['imagen'] }}"
                     alt="{{ $hotel['nombre'] }}">

                <div class="detalle-columnas">
                    <section class="detalle-info">
                        <h2>Acerca de este alojamiento</h2>
                        <p>{{ $hotel['descripcion'] }}</p>

                        <h2>Servicios disponibles</h2>
                        <div class="detalle-servicios">
                            @foreach($hotel['servicios'] as $servicio)
                                <span>{{ $servicio }}</span>
                            @endforeach
                        </div>
                    </section>

                    <aside class="detalle-reserva">
                        <p>Precio referencial por noche</p>
                        <strong>S/ {{ $hotel['precio'] }}</strong>

                        <button type="button" id="reservarHotel">
                            Reservar alojamiento
                        </button>

                        <small>
                            Demostración: no se realizará ningún cobro.
                        </small>
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const boton = document.getElementById('reservarHotel');

            if (boton) {
                boton.addEventListener('click', () => {
                    alert('Próximamente podrás reservar este alojamiento.');
                });
            }
        });
    </script>
</x-layout>