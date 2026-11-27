<x-layout>
    <x-slot:titulo>
        World Travel | Confirmar reserva de oferta
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <x-checkout-estilos />

    <main class="wt-checkout">
        <div class="checkout-container">

            <x-checkout-cabecera
                titulo="Confirma tu reserva de oferta"
                descripcion="Aprovecha el descuento vigente y completa los datos para registrar tu reserva."
                eyebrow="WORLD TRAVEL / OFERTAS Y PROMOCIONES"
                volver="{{ route('ofertas.detalle', $oferta) }}"
            />

            <div class="checkout-grid">

                <x-checkout-datos-titular
                    action="{{ route('reservas.ofertas.store') }}"
                    :user="$user"
                    titulo="Información del viajero titular"
                    descripcion="Ingresa los datos del pasajero responsable de la reserva promocional."
                    nota="No se realizará ningún cobro. La reserva quedará registrada en la base de datos de World Travel con estado Pendiente."
                >
                    <x-slot:hidden>
                        <input type="hidden" name="oferta_id" value="{{ $oferta->id }}">
                        <input type="hidden" name="fecha_viaje" value="{{ $fechaViaje }}">
                    </x-slot:hidden>

                    <x-slot:extra>
                        <div class="checkout-campo">
                            <label for="numPasajeros">Cantidad de viajeros *</label>
                            <select name="num_pasajeros" id="numPasajeros">
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ (int) old('num_pasajeros', $numPasajeros) === $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i === 1 ? 'persona' : 'personas' }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="checkout-campo">
                            <label for="comentariosViajero">
                                Solicitudes especiales
                                <span class="opcional">(Opcional)</span>
                            </label>
                            <textarea id="comentariosViajero"
                                      name="comentarios"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Indica cualquier requerimiento especial para tu viaje..."></textarea>
                        </div>
                    </x-slot:extra>
                </x-checkout-datos-titular>

                <aside class="checkout-card checkout-resumen">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">02</span>
                        <div>
                            <h2>Resumen de la oferta</h2>
                            <p>Promoción aplicada y cotización</p>
                        </div>
                    </div>

                    <div class="checkout-servicio">
                        <strong>{{ $oferta->titulo }}</strong>
                        Descuento del {{ $oferta->descuento_porcentaje }}% vigente hasta el {{ $oferta->fecha_fin->format('d/m/Y') }}
                    </div>

                    @if ($paquete->imagen_url)
                        <img class="checkout-imagen"
                             src="{{ str_starts_with($paquete->imagen_url, 'http') ? $paquete->imagen_url : asset('icons/' . ltrim($paquete->imagen_url, '/')) }}"
                             alt="{{ $paquete->destino }}"
                             onerror="this.onerror=null; this.style.visibility='hidden';">
                    @endif

                    <div class="checkout-detalle">
                        <h3>{{ $paquete->titulo }}</h3>
                        <p>Destino: <strong>{{ $paquete->destino }}</strong> ({{ $paquete->duracion_dias }} días)</p>
                    </div>

                    <div class="checkout-fila">
                        <span>Precio habitual por persona</span>
                        <strong><s>US$ {{ number_format($precioOriginal, 2) }}</s></strong>
                    </div>

                    <div class="checkout-fila" style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 9px 12px; border-radius: 8px; margin: 8px 0;">
                        <span style="color: #166534; font-weight: 600;">Precio promocional por persona</span>
                        <strong style="color: #15803d; font-size: 15px;">US$ {{ number_format($precioFinal, 2) }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Viajeros</span>
                        <strong>{{ $numPasajeros }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Fecha de viaje</span>
                        <strong>{{ \Carbon\Carbon::parse($fechaViaje)->format('d/m/Y') }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Alojamiento</span>
                        <strong>{{ $paquete->incluye_hotel ? 'Incluido' : 'No incluido' }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Vuelo</span>
                        <strong>{{ $paquete->incluye_vuelo ? 'Incluido' : 'No incluido' }}</strong>
                    </div>

                    <div class="checkout-total">
                        <span>Total estimado</span>
                        <strong>US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <p class="checkout-aclaracion">
                        Ahorras US$ {{ number_format($precioOriginal - $precioFinal, 2) }} por persona respecto al precio habitual.
                    </p>
                </aside>
            </div>

        </div>
    </main>
</x-layout>
