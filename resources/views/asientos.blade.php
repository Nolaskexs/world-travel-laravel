<x-layout>
    <x-slot:titulo>
        {{ !empty($reserva) ? 'Check-in y Asientos' : 'Selección de Asientos' }} | World Travel
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/asientos.css', 'resources/js/asientos.js'])
    </x-slot:recursos>

@php
    $vuelo            = $vuelo ?? (object) ['id' => 1, 'avion' => 'Boeing 767-300 World Travel', 'origen' => 'Lima (LIM)', 'destino' => 'Tokio (HND)', 'numero' => 'WT 8183', 'precio_base' => 2269.00];
    $asientosOcupados = $asientosOcupados ?? ['1A', '2C', '3D', '12A', '14F', '15D', '18J', '22C', '24L', '27H', '30A', '30C', '33F'];
    $tarifas          = $tarifas ?? ['xl' => 35, 'biz' => 120];
    $moneda           = $moneda ?? 'USD';
    $baseTotal        = $baseTotal ?? 0;
    $pasajeros        = $pasajeros ?? [
        ['nombre' => 'Adulto 1', 'codigo' => 'A1', 'asiento' => null]
    ];
    $infantes         = $infantes ?? 0;

    // Construcción del mapa del avión
    $ocupados = array_flip($asientosOcupados);
    $asiento = function (int $fila, string $col, string $clase) use ($ocupados, $tarifas) {
        return [
            't'       => 'seat',
            'code'    => $fila . $col,
            'clase'   => $clase,
            'precio'  => $clase === 'std' ? 0 : $tarifas[$clase],
            'ocupado' => isset($ocupados[$fila . $col])
        ];
    };
    $esXL = fn (int $f, string $c) => in_array($f, [12, 13, 23]) || ($f === 22 && in_array($c, ['A', 'C', 'L']));

    $mapa = [];
    foreach (range(1, 5) as $f) {
        $conAsiento = $f % 2 === 1 ? ['A', 'D', 'F', 'J'] : ['C', 'E', 'H', 'L'];
        $celdas = [];
        foreach (['A', 'C', '|', 'D', 'E', 'F', 'H', '|', 'J', 'L'] as $c) {
            $celdas[] = $c === '|' ? ['t' => 'aisle'] : (in_array($c, $conAsiento) ? $asiento($f, $c, 'biz') : ['t' => 'console']);
        }
        $mapa[] = ['n' => $f, 'zona' => 'business', 'cabina' => $f === 1 ? 'Premium Business' : null, 'celdas' => $celdas];
    }
    foreach (range(12, 44) as $f) {
        $celdas = [];
        foreach (['A', 'C', '|', 'D', 'F', 'H', '|', 'J', 'L'] as $c) {
            $celdas[] = $c === '|' ? ['t' => 'aisle'] : $asiento($f, $c, $esXL($f, $c) ? 'xl' : 'std');
        }
        $mapa[] = [
            'n'              => $f,
            'zona'           => 'eco',
            'cabina'         => $f === 12 ? 'Economy' : null,
            'celdas'         => $celdas,
            'ala'            => $f === 21,
            'salida_antes'   => $f === 22,
            'salida_despues' => $f === 22
        ];
    }
    $nombresClase = ['std' => 'estándar', 'xl' => 'XL con más espacio', 'biz' => 'Premium Business'];
@endphp

<div class="seats-page" data-seats data-currency="{{ $moneda }}" data-base-total="{{ $baseTotal }}">
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></symbol>
        <symbol id="i-check" viewBox="0 0 24 24"><path d="m5 12 5 5L20 7"/></symbol>
        <symbol id="i-down" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
        <symbol id="i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16.5h.01"/></symbol>
        <symbol id="i-bed" viewBox="0 0 24 24"><path d="M2 5v14M2 9h18a2 2 0 0 1 2 2v8M2 16h20M6 9v7"/></symbol>
    </svg>

    <div style="max-width: 1500px; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: space-between;">
        <a href="{{ url()->previous() ?? route('inicio') }}" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: #1e293b; text-decoration: none;">
            &larr; Volver
        </a>
        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600;">
            @if (!empty($reserva))
                <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 999px;">Check-in activo · Reserva #{{ $reserva->id }}</span>
            @else
                <span>Paso 2 de 3 · Configuración de vuelo</span>
            @endif
        </div>
    </div>

    <form id="seatForm" class="seats-grid" method="POST" action="{{ route('asientos.guardar') }}">
        @csrf
        @if (!empty($reserva))
            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
        @else
            <input type="hidden" name="vuelo_id" value="{{ request('vuelo_id') ?? data_get($vuelo, 'id') }}">
            <input type="hidden" name="paquete_id" value="{{ request('paquete_id') }}">
            <input type="hidden" name="oferta_id" value="{{ request('oferta_id') }}">
            <input type="hidden" name="fecha_viaje" value="{{ request('fecha_viaje') }}">
            <input type="hidden" name="num_pasajeros" value="{{ count($pasajeros) }}">
            <input type="hidden" name="origin" value="{{ request('origin') }}">
            <input type="hidden" name="destination" value="{{ request('destination') }}">
            <input type="hidden" name="origin_iata" value="{{ request('origin_iata') }}">
            <input type="hidden" name="destination_iata" value="{{ request('destination_iata') }}">
            <input type="hidden" name="codigo_vuelo" value="{{ request('codigo_vuelo') ?? data_get($vuelo, 'numero') }}">
            <input type="hidden" name="precio" value="{{ request('precio') ?? data_get($vuelo, 'precio_base') }}">
            <input type="hidden" name="adultos" value="{{ request('adultos') ?? $adultos }}">
            <input type="hidden" name="menores" value="{{ request('menores') ?? $menores }}">
        @endif

        <div id="seatInputs" hidden></div>

        {{-- Columna izquierda: categorías y leyenda --}}
        <aside class="col-left" aria-label="Categorías y leyenda">
            <section class="card biz">
                <button class="biz__head" type="button" aria-expanded="true" aria-controls="biz-list">
                    <span class="biz__ico"><svg class="i"><use href="#i-bed"/></svg></span>
                    <span>Premium Business</span>
                    <svg class="i biz__chev"><use href="#i-down"/></svg>
                </button>
                <div class="collapse is-open" id="biz-list">
                    <ul class="checks">
                        <li><svg class="i"><use href="#i-check"/></svg>Asiento cama reclinable 180°</li>
                        <li><svg class="i"><use href="#i-check"/></svg>Gastronomía premium a bordo</li>
                        <li><svg class="i"><use href="#i-check"/></svg>Embarque y desembarque prioritario</li>
                    </ul>
                </div>
            </section>

            <section class="card legend" aria-label="Leyenda de asientos">
                <ul>
                    <li><span class="seat seat--std seat--static"></span>Disponible</li>
                    <li><span class="seat seat--xl seat--static"><span class="seat__tag">XL</span></span>Asiento XL (+{{ $moneda }} {{ $tarifas['xl'] }})</li>
                    <li><span class="seat seat--biz seat--static"></span>Premium Business (+{{ $moneda }} {{ $tarifas['biz'] }})</li>
                    <li><span class="seat seat--std seat--static is-selected"></span>Seleccionado</li>
                    <li><span class="seat seat--std seat--static" data-legend-taken><span class="seat__x"><svg class="i"><use href="#i-x"/></svg></span></span>Asiento no disponible</li>
                </ul>
            </section>
        </aside>

        {{-- Columna central: mapa del fuselaje --}}
        <main class="col-center">
            <div class="map-scroll" tabindex="0" aria-label="Mapa de asientos del avión">
                <div class="fuselage">
                    <header class="nose">
                        <h1>World Travel</h1>
                        <p>{{ data_get($vuelo, 'origen') }} &rarr; {{ data_get($vuelo, 'destino') }} · {{ data_get($vuelo, 'numero') }}</p>
                    </header>

                    @foreach ($mapa as $fila)
                        @if ($fila['cabina'])
                            <p class="cabin">{{ $fila['cabina'] }}</p>
                        @endif
                        @if (!empty($fila['salida_antes']))
                            <div class="exit" role="separator"><span>SALIDA DE EMERGENCIA</span></div>
                        @endif

                        <div class="row row--{{ $fila['zona'] }}">
                            @foreach ($fila['celdas'] as $c)
                                @if ($c['t'] === 'aisle')
                                    <span class="row__n" aria-hidden="true">{{ $fila['n'] }}</span>
                                @elseif ($c['t'] === 'console')
                                    <span class="console" aria-hidden="true"></span>
                                @else
                                    <button type="button" class="seat seat--{{ $c['clase'] }}"
                                            data-seat="{{ $c['code'] }}" data-type="{{ $c['clase'] }}" data-price="{{ $c['precio'] }}"
                                            aria-pressed="false" @disabled($c['ocupado'])
                                            aria-label="Asiento {{ $c['code'] }}, {{ $nombresClase[$c['clase']] }}{{ $c['ocupado'] ? ', no disponible' : '' }}">
                                        @if ($c['clase'] === 'xl')<span class="seat__tag" aria-hidden="true">XL</span>@endif
                                        <span class="seat__x" aria-hidden="true"><svg class="i"><use href="#i-x"/></svg></span>
                                        <span class="seat__lbl" aria-hidden="true"></span>
                                    </button>
                                @endif
                            @endforeach

                            @if (!empty($fila['ala']))
                                @foreach (['l', 'r'] as $lado)
                                    <svg class="wing wing--{{ $lado }}" viewBox="0 0 300 320" preserveAspectRatio="xMaxYMin meet" aria-hidden="true">
                                        <path class="wing__body" d="M300 0 L8 182 Q0 187 0 196 L0 252 Q0 262 10 261 L300 170 Z"/>
                                        <path class="wing__line" d="M300 16 L18 196M300 52 L28 232"/>
                                        <rect class="wing__body" x="150" y="62" width="58" height="100" rx="26"/>
                                        <path class="wing__line" d="M154 82Q179 68 204 82"/>
                                    </svg>
                                @endforeach
                            @endif
                        </div>

                        @if (!empty($fila['salida_despues']))
                            <div class="exit" role="separator"><span>SALIDA DE EMERGENCIA</span></div>
                        @endif
                    @endforeach

                    <div class="tail" aria-hidden="true"></div>
                </div>
            </div>
        </main>

        {{-- Columna derecha: pasajeros y resumen --}}
        <aside class="col-right card" aria-label="Pasajeros y resumen">
            <div class="pax-panel">
                <h2>Pasajeros</h2>

                <div class="notice" role="note" data-notice>
                    <svg class="i"><use href="#i-alert"/></svg>
                    <p>Selecciona un pasajero y luego haz clic en su asiento preferido dentro del mapa del avión.</p>
                    <button type="button" data-notice-close aria-label="Cerrar aviso"><svg class="i"><use href="#i-x"/></svg></button>
                </div>

                <ul class="pax-list">
                    @foreach ($pasajeros as $i => $p)
                        <li class="pax" data-pax data-name="{{ $p['nombre'] }}" data-code="{{ $p['codigo'] }}" data-initial-seat="{{ $p['asiento'] }}">
                            <span class="pax__tag">Estás seleccionando asiento para:</span>
                            <button class="pax__btn" type="button" aria-pressed="false">
                                <span class="pax__seat" data-pax-seat>{{ $p['asiento'] ?? '--' }}</span>
                                <span class="pax__txt">
                                    <strong class="trunc">{{ $p['nombre'] }}</strong>
                                    <small class="trunc" data-pax-sub>{{ $p['asiento'] ? 'Asiento ' . $p['asiento'] : 'Sin selección' }}</small>
                                </span>
                            </button>
                        </li>
                    @endforeach
                </ul>

                @if ($infantes > 0)
                    <p class="muted">{{ $infantes }} {{ $infantes === 1 ? 'infante viaja' : 'infantes viajan' }} en brazos de un adulto y no requiere asiento separado.</p>
                @endif
                <p class="sr" role="status" aria-live="polite" id="seatMsg"></p>
            </div>

            <div class="summary">
                <button class="summary__toggle" type="button" aria-expanded="true" aria-controls="breakdown">
                    <span>Precio total asientos <svg class="i"><use href="#i-down"/></svg></span>
                    <strong data-total>{{ $moneda }} 0.00</strong>
                </button>
                <dl class="breakdown" id="breakdown">
                    <div>
                        <dt>Tarifa base ({{ count($pasajeros) }} {{ count($pasajeros) === 1 ? 'pasajero' : 'pasajeros' }})</dt>
                        <dd data-base>{{ $moneda }} {{ number_format($baseTotal, 2) }}</dd>
                    </div>
                    <div data-line="xl" hidden><dt>Asientos XL (<span data-count>0</span>)</dt><dd data-amount></dd></div>
                    <div data-line="biz" hidden><dt>Asientos Business (<span data-count>0</span>)</dt><dd data-amount></dd></div>
                </dl>
                <p class="summary__hint muted" data-missing></p>
                <div class="summary__actions">
                    <button class="btn-seat btn-seat--primary" type="submit" data-continue disabled>
                        {{ !empty($reserva) ? 'Confirmar Check-in y Asientos' : 'Continuar a confirmación' }}
                    </button>
                    @if (empty($reserva))
                        <button class="btn-seat btn-seat--ghost" type="submit" name="omitir" value="1" formnovalidate>
                            Omitir y elegir asientos después &rarr;
                        </button>
                    @endif
                </div>
            </div>
        </aside>
    </form>
</div>
</x-layout>
