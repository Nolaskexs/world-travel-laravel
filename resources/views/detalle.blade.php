<x-layout>
    <x-slot:titulo>World Travel | Detalle del Alojamiento</x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <div class="wt-hoteles">
        <section class="hotel-hero">
            <div class="hotel-container">
                <span class="hotel-etiqueta">WORLD TRAVEL</span>
                <h1>Detalle del Alojamiento</h1>
                <p>Información sobre tu alojamiento seleccionado.</p>
            </div>
        </section>

        <section class="hotel-container hotel-contenido">
            <div class="hotel-encabezado">
                <div>
                    <span class="hotel-etiqueta texto-azul">DETALLE</span>
                    <h2>Alojamiento en {{ $codigo }}</h2>
                    <p>Descubre más información sobre este destino.</p>
                </div>
                <div class="hotel-filtro">
                    <a href="{{ route('alojamientos') }}" class="btn-volver">← Volver a alojamientos</a>
                </div>
            </div>

            <div class="hotel-detalle">
                <div class="hotel-detalle-imagen">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=900" alt="Alojamiento en {{ $codigo }}" loading="lazy">
                </div>
                <div class="hotel-detalle-info">
                    <h3>{{ $codigo }}</h3>
                    <p class="hotel-ubicacion">{{ $codigo }}, Perú</p>
                    <div class="hotel-servicios">
                        <span>Wi-Fi</span> · <span>Desayuno</span> · <span>Piscina</span>
                    </div>
                    <div class="hotel-precio">
                        <small>Precio referencial / noche</small>
                        <strong>S/ 250</strong>
                    </div>
                    <button class="hotel-boton">Reservar ahora</button>
                </div>
            </div>
        </section>
    </div>

    <style>
        .btn-volver {
            display: inline-block;
            padding: 8px 16px;
            background: #0b2535;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 10px;
        }
        .btn-volver:hover {
            background: #1a3a52;
        }
        .hotel-detalle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        .hotel-detalle-imagen img {
            width: 100%;
            border-radius: 8px;
        }
        .hotel-detalle-info h3 {
            margin-top: 0;
        }
        .hotel-precio {
            margin: 20px 0;
        }
        .hotel-precio strong {
            font-size: 24px;
            color: #0b2535;
        }
        @media (max-width: 768px) {
            .hotel-detalle {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-layout>