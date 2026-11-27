<x-layout>
    <x-slot:titulo>
        World Travel | Confirmar reserva de vuelo
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <x-checkout-estilos />

    <main class="wt-checkout">
        <div class="checkout-container">

            <x-checkout-cabecera
                titulo="Confirma tu reserva de vuelo"
                descripcion="Verifica la ruta, los pasajeros y los asientos antes de registrar tu vuelo."
                eyebrow="WORLD TRAVEL / VUELOS"
                volver="{{ route('asientos.index', array_filter([
                    'vuelo_id' => $vuelo->id ?? null,
                    'origin' => $origen,
                    'destination' => $destino,
                    'origin_iata' => $origenIata,
                    'destination_iata' => $destinoIata,
                    'codigo_vuelo' => $codigoVuelo,
                    'precio' => $precioAdulto,
                    'fecha_viaje' => $fechaViaje,
                    'adultos' => $adultos,
                    'menores' => $menores,
                ])) }}"
            />

            <div class="checkout-grid">

                <x-checkout-datos-titular
                    action="{{ route('reservas.vuelos.store') }}"
                    :user="$user"
                    titulo="Información del pasajero titular"
                    descripcion="Ingresa los datos del pasajero responsable de este vuelo."
                    documentoLabel="Tipo y número de documento"
                    nota="No se realizará ningún cobro. La reserva quedará registrada en la base de datos de World Travel con estado Pendiente."
                >
                    <x-slot:hidden>
                        <input type="hidden" name="vuelo_id" value="{{ $vuelo->id ?? '' }}">
                        <input type="hidden" name="origen" value="{{ $origen }}">
                        <input type="hidden" name="destino" value="{{ $destino }}">
                        <input type="hidden" name="origin_iata" value="{{ $origenIata }}">
                        <input type="hidden" name="destination_iata" value="{{ $destinoIata }}">
                        <input type="hidden" name="codigo_vuelo" value="{{ $codigoVuelo }}">
                        <input type="hidden" name="precio_adulto" value="{{ $precioAdulto }}">
                        <input type="hidden" name="impuestos" value="{{ $impuestos }}">
                        <input type="hidden" name="fecha_viaje" value="{{ $fechaViaje }}">
                        <input type="hidden" name="asientos" value="{{ old('asientos', $asientos) }}">
                        <input type="hidden" name="adultos" value="{{ $adultos }}">
                        <input type="hidden" name="menores" value="{{ $menores }}">
                        <input type="hidden" name="infantes" value="{{ $infantes }}">
                    </x-slot:hidden>

                    <x-slot:extra>
                        <div class="checkout-campo">
                            <label for="tipoDocumento">Tipo de documento *</label>
                            <select name="tipo_documento" id="tipoDocumento">
                                <option value="DNI" {{ old('tipo_documento', 'DNI') === 'DNI' ? 'selected' : '' }}>DNI</option>
                                <option value="Pasaporte" {{ old('tipo_documento') === 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
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
                                      placeholder="Requerimientos de equipaje, asistencia o comidas especiales..."></textarea>
                        </div>
                    </x-slot:extra>
                </x-checkout-datos-titular>

                <aside class="checkout-card checkout-resumen">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">02</span>
                        <div>
                            <h2>Resumen del vuelo</h2>
                            <p>Ruta, tarifa y cotización</p>
                        </div>
                    </div>

                    <div class="checkout-servicio">
                        <strong>{{ $codigoVuelo }} · {{ $vuelo->aerolinea ?? 'World Travel' }}</strong>
                        {{ $origen }} ({{ $origenIata }}) &rarr; {{ $destino }} ({{ $destinoIata }})
                    </div>

                    <div class="checkout-detalle">
                        <h3>{{ $origenIata }} &rarr; {{ $destinoIata }}</h3>
                        <p>Salida: {{ \Carbon\Carbon::parse($fechaViaje)->format('d/m/Y') }}</p>
                    </div>

                    <div class="checkout-fila">
                        <span>Duración estimada</span>
                        <strong>{{ $vuelo->duracion_formateada ?? 'Por definir' }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Escalas</span>
                        <strong>{{ $vuelo->cantidad_escalas ?? 0 }}</strong>
                    </div>

                    @if (!empty($asientos))
                        <div class="checkout-fila" style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 9px 12px; border-radius: 8px; margin: 8px 0;">
                            <span style="color: #1e40af; font-weight: 600;">Asientos seleccionados</span>
                            <strong style="color: #1d4ed8; font-size: 14px;">{{ $asientos }}</strong>
                        </div>
                    @else
                        <div style="margin: 8px 0; text-align: right;">
                            <a href="{{ route('asientos.index', array_filter([
                                'vuelo_id' => $vuelo->id ?? null,
                                'origin' => $origen,
                                'destination' => $destino,
                                'origin_iata' => $origenIata,
                                'destination_iata' => $destinoIata,
                                'codigo_vuelo' => $codigoVuelo,
                                'precio' => $precioAdulto,
                                'fecha_viaje' => $fechaViaje,
                                'adultos' => $adultos,
                                'menores' => $menores,
                            ])) }}"
                               class="checkout-enlace">
                                Seleccionar asientos en el mapa &rarr;
                            </a>
                        </div>
                    @endif

                    <div class="checkout-fila">
                        <span>Adultos ({{ $adultos }} × US$ {{ number_format($precioAdulto, 2) }})</span>
                        <strong>US$ {{ number_format($precioAdulto * $adultos, 2) }}</strong>
                    </div>

                    @if ($menores > 0)
                        <div class="checkout-fila">
                            <span>Menores ({{ $menores }} × US$ {{ number_format($precioMenor, 2) }})</span>
                            <strong>US$ {{ number_format($precioMenor * $menores, 2) }}</strong>
                        </div>
                    @endif

                    @if ($infantes > 0)
                        <div class="checkout-fila">
                            <span>Infantes ({{ $infantes }} × US$ {{ number_format($precioInfante, 2) }})</span>
                            <strong>US$ {{ number_format($precioInfante * $infantes, 2) }}</strong>
                        </div>
                    @endif

                    <div class="checkout-fila">
                        <span>Impuestos y tasas</span>
                        <strong>US$ {{ number_format($impuestos * $numPasajeros, 2) }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Total de pasajeros</span>
                        <strong>{{ $numPasajeros }}</strong>
                    </div>

                    <div class="checkout-total">
                        <span>Total estimado</span>
                        <strong>US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <p class="checkout-aclaracion">
                        Tarifas expresadas en USD. Los asientos elegidos se conservan en la reserva.
                    </p>
                </aside>
            </div>

        </div>
    </main>
</x-layout>
