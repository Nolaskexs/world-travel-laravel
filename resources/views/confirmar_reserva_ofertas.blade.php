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
                        <input type="hidden" name="asientos" id="inputAsientos" value="{{ old('asientos', $asientos ?? '') }}">
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
                        <strong id="displayPasajeros">{{ $numPasajeros }}</strong>
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

                    @if ($paquete->incluye_vuelo)
                        @if (!empty($asientos))
                            <div class="checkout-fila" style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 9px 12px; border-radius: 8px; margin: 8px 0;">
                                <span style="color: #1e40af; font-weight: 600;">Asientos asignados</span>
                                <strong style="color: #1d4ed8; font-size: 14px;">{{ $asientos }}</strong>
                            </div>
                        @else
                            <div style="margin: 8px 0; text-align: right;">
                                <a id="btnElegirAsientosOferta"
                                   href="{{ route('asientos.index', ['oferta_id' => $oferta->id, 'adultos' => $numPasajeros, 'fecha_viaje' => $fechaViaje]) }}"
                                   class="checkout-enlace">
                                    Seleccionar asientos en el mapa &rarr;
                                </a>
                            </div>
                        @endif
                    @endif

                    <div class="checkout-fila">
                        <span>Subtotal</span>
                        <strong id="displaySubtotal">US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <div class="checkout-total">
                        <span>Total estimado</span>
                        <div style="text-align: right;">
                            <strong id="displayTotal" style="display: block;">US$ {{ number_format($totalEstimado, 2) }}</strong>
                            <small id="displayTotalSoles" style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-top: 2px;">
                                aprox. S/ {{ number_format($totalEstimado * 3.51, 2) }}
                            </small>
                        </div>
                    </div>

                    <p class="checkout-aclaracion">
                        Ahorras US$ {{ number_format($precioOriginal - $precioFinal, 2) }} por persona respecto al precio habitual. El registro se procesará con estado inicial <strong>Pendiente</strong>.
                    </p>
                </aside>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectNumPasajeros = document.getElementById('numPasajeros');
            const displayPasajeros = document.getElementById('displayPasajeros');
            const displaySubtotal = document.getElementById('displaySubtotal');
            const displayTotal = document.getElementById('displayTotal');
            const displayTotalSoles = document.getElementById('displayTotalSoles');
            const btnAsientos = document.getElementById('btnElegirAsientosOferta');
            const precioUnitario = {{ (float) $precioFinal }};
            const tipoCambio = 3.51;

            const formatMoney = (val) => 'US$ ' + Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const formatSoles = (val) => 'aprox. S/ ' + Number(val * tipoCambio).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            function recalcular() {
                const pax = parseInt(selectNumPasajeros ? selectNumPasajeros.value : 1, 10) || 1;
                const total = Math.round(precioUnitario * pax * 100) / 100;

                if (displayPasajeros) displayPasajeros.textContent = pax;
                if (displaySubtotal) displaySubtotal.textContent = formatMoney(total);
                if (displayTotal) displayTotal.textContent = formatMoney(total);
                if (displayTotalSoles) displayTotalSoles.textContent = formatSoles(total);

                if (btnAsientos) {
                    try {
                        const url = new URL(btnAsientos.href, window.location.origin);
                        url.searchParams.set('adultos', pax);
                        btnAsientos.href = url.pathname + url.search;
                    } catch (e) {}
                }
            }

            if (selectNumPasajeros) {
                selectNumPasajeros.addEventListener('change', recalcular);
                selectNumPasajeros.addEventListener('input', recalcular);
            }
        });
    </script>
</x-layout>
