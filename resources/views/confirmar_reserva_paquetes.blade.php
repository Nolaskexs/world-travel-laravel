<x-layout>
    <x-slot:titulo>
        World Travel | Confirmar reserva de paquete
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <x-checkout-estilos />

    <main class="wt-checkout">
        <div class="checkout-container">

            <x-checkout-cabecera
                titulo="Confirma tu reserva de paquete"
                descripcion="Completa tus datos para registrar el paquete seleccionado en la base de datos de World Travel."
                eyebrow="WORLD TRAVEL / PAQUETES Y TOURS"
            />

            <div class="checkout-grid" id="checkoutContenido">

                <x-checkout-datos-titular
                    action="{{ route('reservas.store') }}"
                    :user="$user"
                    nota="El registro se procesará con estado inicial Pendiente y quedará almacenado en la base de datos de World Travel."
                >
                    <x-slot:hidden>
                        <input type="hidden" name="paquete_id" id="inputPaqueteId" value="{{ $paqueteSeleccionado->id ?? ($paquetes->first()->id ?? 1) }}">
                        <input type="hidden" name="total" id="inputTotal" value="{{ $totalEstimado ?? 350 }}">
                        <input type="hidden" name="asientos" id="inputAsientos" value="{{ old('asientos', $asientos ?? '') }}">
                    </x-slot:hidden>

                    <x-slot:extra>
                        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 15px;">
                            <div class="checkout-campo">
                                <label for="fechaViaje">Fecha de viaje *</label>
                                <input type="date"
                                       name="fecha_viaje"
                                       id="fechaViaje"
                                       value="{{ old('fecha_viaje', $fechaViaje) }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="checkout-campo">
                                <label for="numPasajeros">Cantidad de personas *</label>
                                <select name="num_pasajeros" id="numPasajeros">
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ (int) old('num_pasajeros', $numPasajeros) === $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i === 1 ? 'persona' : 'personas' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
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
                                      placeholder="Asientos juntos, comidas especiales, cuna o requerimientos de accesibilidad..."></textarea>
                        </div>
                    </x-slot:extra>
                </x-checkout-datos-titular>

                <!-- RESUMEN DEL PAQUETE / VUELO -->
                <aside class="checkout-card checkout-resumen">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">02</span>
                        <div>
                            <h2>Resumen de tu viaje</h2>
                            <p>Detalles del paquete y cotización</p>
                        </div>
                    </div>

                    @php
                        $resolverImagen = function ($pkg) {
                            $url = $pkg->imagen_url ?? null;
                            if (!empty($url)) {
                                if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                                    return $url;
                                }
                                if (str_starts_with($url, 'icons/')) {
                                    return asset($url);
                                }
                                return asset('icons/' . ltrim($url, '/'));
                            }
                            if (isset($pkg->destino) && stripos($pkg->destino, 'cusco') !== false) {
                                return asset('icons/MachuPicchu1-Carrusel.jpg');
                            }
                            return asset('icons/roma.jpg');
                        };

                        $imgPrincipal = $resolverImagen($paqueteSeleccionado);
                    @endphp

                    <div class="checkout-campo" style="margin-bottom: 16px;">
                        <label for="selectPaquete" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #5b6e7f; font-weight: 700;">Paquete o tour</label>
                        <select id="selectPaquete">
                            @foreach ($paquetes as $pkg)
                                @php
                                    $imgUrl = $resolverImagen($pkg);
                                @endphp
                                <option value="{{ $pkg->id }}"
                                        data-precio="{{ $pkg->precio }}"
                                        data-destino="{{ $pkg->destino }}"
                                        data-dias="{{ $pkg->duracion_dias }}"
                                        data-imagen="{{ $imgUrl }}"
                                        {{ ($paqueteSeleccionado->id ?? null) === $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->titulo }} · US$ {{ number_format($pkg->precio, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <img id="imagenPaquete"
                         class="checkout-imagen"
                         src="{{ $imgPrincipal }}"
                         alt="{{ $paqueteSeleccionado->titulo ?? 'Paquete turístico' }}"
                         onerror="this.onerror=null; this.src='{{ asset('icons/MachuPicchu1-Carrusel.jpg') }}';">

                    <div class="checkout-detalle">
                        <h3 id="paqueteTitulo">
                            {{ $paqueteSeleccionado->titulo ?? 'Paquete Turístico' }}
                        </h3>
                        <p id="paqueteUbicacion" class="checkout-ubicacion">
                            Destino: <strong>{{ $paqueteSeleccionado->destino ?? 'Varios' }}</strong> ({{ $paqueteSeleccionado->duracion_dias ?? 5 }} días)
                        </p>
                    </div>

                    <div class="checkout-fila">
                        <span>Precio por persona</span>
                        <strong id="displayPrecioUnitario">US$ {{ number_format($paqueteSeleccionado->precio ?? 350, 2) }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Cantidad de viajeros</span>
                        <strong id="displayPasajeros">{{ $numPasajeros }}</strong>
                    </div>

                    @if (!empty($asientos))
                        <div class="checkout-fila" style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 9px 12px; border-radius: 8px; margin: 8px 0;">
                            <span style="color: #1e40af; font-weight: 600;">Asientos asignados</span>
                            <strong style="color: #1d4ed8; font-size: 14px;">{{ $asientos }}</strong>
                        </div>
                    @else
                        <div style="margin: 8px 0; text-align: right;">
                            <a href="{{ route('asientos.index', ['paquete_id' => $paqueteSeleccionado->id ?? 1, 'adultos' => $numPasajeros, 'fecha_viaje' => $fechaViaje]) }}"
                               class="checkout-enlace">
                                Seleccionar asientos en el mapa &rarr;
                            </a>
                        </div>
                    @endif

                    <div class="checkout-fila">
                        <span>Subtotal</span>
                        <strong id="displaySubtotal">US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <div class="checkout-total">
                        <span>Total estimado</span>
                        <strong id="displayTotal">US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <p class="checkout-aclaracion">
                        El registro se procesará con estado inicial <strong>Pendiente</strong>. Podrás verificarlo de inmediato en tu panel.
                    </p>
                </aside>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectPaquete = document.getElementById('selectPaquete');
            const selectNumPasajeros = document.getElementById('numPasajeros');
            const inputPaqueteId = document.getElementById('inputPaqueteId');
            const inputTotal = document.getElementById('inputTotal');

            const displayPrecioUnitario = document.getElementById('displayPrecioUnitario');
            const displayPasajeros = document.getElementById('displayPasajeros');
            const displaySubtotal = document.getElementById('displaySubtotal');
            const displayTotal = document.getElementById('displayTotal');
            const paqueteTitulo = document.getElementById('paqueteTitulo');
            const paqueteUbicacion = document.getElementById('paqueteUbicacion');
            const imagenPaquete = document.getElementById('imagenPaquete');

            const formatMoney = (val) => 'US$ ' + Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            function recalcular() {
                const opt = selectPaquete.options[selectPaquete.selectedIndex];
                if (!opt) return;

                const precio = parseFloat(opt.dataset.precio || 0);
                const pax = parseInt(selectNumPasajeros.value || 1, 10);
                const total = precio * pax;

                inputPaqueteId.value = opt.value;
                inputTotal.value = total.toFixed(2);

                displayPrecioUnitario.textContent = formatMoney(precio);
                displayPasajeros.textContent = pax;
                displaySubtotal.textContent = formatMoney(total);
                displayTotal.textContent = formatMoney(total);

                if (paqueteTitulo) paqueteTitulo.textContent = opt.textContent.split('·')[0].trim();
                if (paqueteUbicacion) paqueteUbicacion.innerHTML = `Destino: <strong>${opt.dataset.destino || 'Varios'}</strong> (${opt.dataset.dias || 5} días)`;
                if (imagenPaquete && opt.dataset.imagen) {
                    imagenPaquete.src = opt.dataset.imagen;
                    imagenPaquete.alt = opt.textContent.split('·')[0].trim();
                }
            }

            selectPaquete.addEventListener('change', recalcular);
            selectNumPasajeros.addEventListener('change', recalcular);
        });
    </script>
</x-layout>
