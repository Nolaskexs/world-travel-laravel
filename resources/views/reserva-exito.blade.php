<x-layout>
    <x-slot:titulo>
        World Travel | Reserva realizada correctamente
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <x-checkout-estilos />

    <main class="wt-checkout">
        <div class="checkout-container">
            <div class="checkout-card checkout-exito">
                <div class="checkout-exito__icono">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                </div>

                <h1>¡Reserva realizada correctamente!</h1>

                @if (session('success'))
                    <p style="color: #166534; font-weight: 600;">{{ session('success') }}</p>
                @endif

                <p style="color: #718190;">
                    Tu solicitud quedó registrada en la base de datos de World Travel con estado
                    <strong>{{ ucfirst($reserva->estado) }}</strong>.
                </p>

                @php
                    $servicios = [
                        'paquete' => 'Paquete turístico',
                        'alojamiento' => 'Alojamiento',
                        'vuelo' => 'Vuelo',
                        'oferta' => 'Oferta promocional',
                    ];
                    $titular = $reserva->pasajeros->first();
                @endphp

                <div class="checkout-card checkout-exito__col">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">#</span>
                        <div>
                            <h2>Detalle de la reserva</h2>
                            <p>Comprobante de registro de tu servicio</p>
                        </div>
                    </div>

                    <div class="checkout-fila">
                        <span>Número de reserva</span>
                        <strong>#{{ $reserva->id }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Servicio</span>
                        <strong>{{ $servicios[$reserva->tipo_servicio] ?? ucfirst($reserva->tipo_servicio ?? 'Servicio') }}</strong>
                    </div>

                    @if ($reserva->detalle_servicio)
                        <div class="checkout-fila">
                            <span>Detalle</span>
                            <strong>{{ $reserva->detalle_servicio }}</strong>
                        </div>
                    @endif

                    <div class="checkout-fila">
                        <span>Titular</span>
                        <strong>{{ $titular->nombre ?? 'No registrado' }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Fecha de viaje</span>
                        <strong>{{ $reserva->fecha_viaje?->format('d/m/Y') ?? 'Por definir' }}</strong>
                    </div>

                    @if ($reserva->noches)
                        <div class="checkout-fila">
                            <span>Noches</span>
                            <strong>{{ $reserva->noches }}</strong>
                        </div>
                    @endif

                    @if ($reserva->asientos)
                        <div class="checkout-fila">
                            <span>Asientos</span>
                            <strong>{{ $reserva->asientos }}</strong>
                        </div>
                    @endif

                    <div class="checkout-fila">
                        <span>Cantidad de pasajeros</span>
                        <strong>{{ $reserva->num_pasajeros }}</strong>
                    </div>

                    <div class="checkout-total">
                        <span>Total registrado</span>
                        <strong>US$ {{ number_format((float) $reserva->total, 2) }}</strong>
                    </div>
                </div>

                <div class="checkout-exito__acciones">
                    <a href="{{ route('dashboard') }}">Ir a mi panel</a>
                    <a href="{{ route('inicio') }}">Volver al inicio</a>
                </div>
            </div>
        </div>
    </main>
</x-layout>
