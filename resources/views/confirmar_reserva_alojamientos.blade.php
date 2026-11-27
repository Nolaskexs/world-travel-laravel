<x-layout>
    <x-slot:titulo>
        World Travel | Confirmar reserva de alojamiento
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <x-checkout-estilos />

    <main class="wt-checkout">
        <div class="checkout-container">

            <x-checkout-cabecera
                titulo="Confirma tu reserva de alojamiento"
                descripcion="Revisa las fechas de estadía y completa los datos del huésped titular."
                eyebrow="WORLD TRAVEL / ALOJAMIENTOS"
                volver="{{ route('alojamientos.detalle', $alojamiento->codigo) }}"
            />

            <div class="checkout-grid">

                <x-checkout-datos-titular
                    action="{{ route('reservas.alojamientos.store') }}"
                    :user="$user"
                    titulo="Información del huésped titular"
                    descripcion="Ingresa los datos de la persona responsable de la estadía."
                    documentoLabel="Documento del huésped"
                    nota="No se realizará ningún cobro. La reserva quedará registrada en la base de datos de World Travel con estado Pendiente."
                >
                    <x-slot:hidden>
                        <input type="hidden" name="alojamiento_id" value="{{ $alojamiento->id }}">
                        <input type="hidden" name="fecha_entrada" value="{{ $entrada }}">
                        <input type="hidden" name="fecha_salida" value="{{ $salida }}">
                    </x-slot:hidden>

                    <x-slot:extra>
                        <div class="checkout-campo">
                            <label for="numPasajeros">Cantidad de huéspedes *</label>
                            <select name="num_pasajeros" id="numPasajeros">
                                @for ($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ (int) old('num_pasajeros', $huespedes) === $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i === 1 ? 'huésped' : 'huéspedes' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </x-slot:extra>
                </x-checkout-datos-titular>

                <aside class="checkout-card checkout-resumen">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">02</span>
                        <div>
                            <h2>Resumen de la estadía</h2>
                            <p>Detalles del alojamiento y cotización</p>
                        </div>
                    </div>

                    <div class="checkout-servicio">
                        <strong>Alojamiento seleccionado</strong>
                        {{ $alojamiento->nombre }} · {{ $alojamiento->ubicacion }}
                    </div>

                    @php
                        $imagenAlojamiento = str_starts_with($alojamiento->imagen, 'http')
                            ? $alojamiento->imagen
                            : asset($alojamiento->imagen);
                    @endphp

                    <img class="checkout-imagen"
                         src="{{ $imagenAlojamiento }}"
                         alt="{{ $alojamiento->nombre }}"
                         onerror="this.onerror=null; this.style.visibility='hidden';">

                    <div class="checkout-detalle">
                        <h3>{{ strtoupper($alojamiento->tipo) }}</h3>
                        <p>{{ $alojamiento->habitacion ?? 'Habitación estándar' }}</p>
                    </div>

                    <div class="checkout-fila">
                        <span>Precio por noche</span>
                        <strong>US$ {{ number_format($precioNocheUsd, 2) }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Check-in</span>
                        <strong>{{ \Carbon\Carbon::parse($entrada)->format('d/m/Y') }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Check-out</span>
                        <strong>{{ \Carbon\Carbon::parse($salida)->format('d/m/Y') }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Noches</span>
                        <strong>{{ $noches }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Huéspedes</span>
                        <strong>{{ $huespedes }}</strong>
                    </div>

                    @if (!empty($alojamiento->servicios))
                        <div class="checkout-campo" style="margin: 14px 0;">
                            <label>Servicios incluidos</label>
                            <p style="font-size: 13px; color: #6c7c8c; line-height: 1.5;">
                                {{ implode(' · ', array_slice($alojamiento->servicios, 0, 4)) }}
                            </p>
                        </div>
                    @endif

                    <div class="checkout-total">
                        <span>Total estimado</span>
                        <strong>US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <p class="checkout-aclaracion">
                        Precio expresado en USD. El total se calcula para {{ $noches }} {{ $noches === 1 ? 'noche' : 'noches' }}.
                    </p>
                </aside>
            </div>

        </div>
    </main>
</x-layout>
