<x-layout>
    <x-slot:titulo>World Travel | Mis itinerarios</x-slot:titulo>

    <x-slot:recursos>
        <style>
            .wt-itin { --wt-navy:#102b43; --wt-blue:var(--ink); --wt-line:#dce6f0; --wt-muted:#687d90; background:#f4f8fc; padding:38px 16px 76px; color:var(--wt-navy); }
            .wt-itin, .wt-itin * { box-sizing:border-box; }
            .wt-itin__wrap { max-width:1180px; margin:0 auto; }
            .wt-itin__hero { position:relative; padding:42px 48px; margin-bottom:26px; border-radius:24px; color:#fff; overflow:hidden; background:linear-gradient(115deg,rgba(8,31,55,.96),rgba(10,74,130,.88)),url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1600&q=85') center/cover; }
            .wt-itin__kicker { font-size:11px; font-weight:800; letter-spacing:2px; color:#aee0ff; }
            .wt-itin__hero h1 { font-size:clamp(30px,4vw,45px); margin:12px 0; color:#fff; line-height:1.15; }
            .wt-itin__hero p { max-width:610px; margin:0; line-height:1.7; color:#dceaf7; font-size:15px; }
            .wt-itin__hero a { display:inline-block; margin-top:22px; padding:11px 18px; background:#fff; border-radius:10px; color:#0e4675; font-size:13px; font-weight:800; text-decoration:none; }
            .wt-itin__grid { display:grid; grid-template-columns:minmax(0,1.7fr) minmax(300px,.9fr); gap:22px; align-items:start; }
            .wt-itin__card { background:#fff; border:1px solid var(--wt-line); border-radius:20px; padding:26px; box-shadow:0 9px 26px rgba(21,57,91,.05); }
            .wt-itin__head { display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom:20px; }
            .wt-itin__head h2 { margin:0; font-size:22px; }
            .wt-itin__sub { color:var(--wt-muted); font-size:13px; line-height:1.6; margin:5px 0 0; }
            .wt-itin__pill { background:#eaf4ff; color:#0766c9; border-radius:999px; font-weight:700; font-size:11px; padding:7px 11px; white-space:nowrap; }
            .wt-itin__fields { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
            .wt-itin__field { display:flex; flex-direction:column; gap:7px; min-width:0; position:relative; }
            .wt-itin__field--full { grid-column:1/-1; }
            .wt-itin__field label { font-weight:700; font-size:12px; color:#27455d; }
            .wt-itin__field input, .wt-itin__field textarea { width:100%; min-width:0; border:1px solid #d5e1ec; border-radius:10px; padding:12px 13px; background:#f9fbfd; color:#102b43; font-size:14px; font-family:inherit; outline:none; transition:border-color .2s, box-shadow .2s; }
            .wt-itin__field input:focus, .wt-itin__field textarea:focus { border-color:var(--wt-blue); box-shadow:0 0 0 3px rgba(8,117,233,.11); background:#fff; }
            .wt-itin__field textarea { resize:vertical; min-height:74px; }
            .wt-itin__section-title { margin:29px 0 14px; padding-top:23px; border-top:1px solid var(--wt-line); display:flex; justify-content:space-between; gap:12px; align-items:center; }
            .wt-itin__section-title h3 { font-size:17px; margin:0; }
            .wt-itin__add, .wt-itin__remove { border:1px solid #c5e1fa; background:#ecf6ff; color:#0764bd; border-radius:9px; padding:10px 13px; font-weight:700; font-size:12px; cursor:pointer; transition:background .2s; }
            .wt-itin__add:hover, .wt-itin__remove:hover { background:#dcefff; }
            .wt-itin__remove:disabled { opacity:.4; cursor:not-allowed; }
            .wt-itin__stops { display:grid; gap:16px; }
            .wt-itin__stop { background:#f8fbff; border:1px solid var(--wt-line); border-radius:15px; padding:20px; transition:border-color .2s; }
            .wt-itin__stop:focus-within { border-color:#93c5fd; }
            .wt-itin__stop-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; gap:8px; }
            .wt-itin__stop-top strong { font-size:15px; font-weight:700; color:#0f172a; }
            .wt-itin__save { display:block; width:100%; margin-top:24px; padding:15px 18px; background:var(--wt-blue); color:#fff; border:0; border-radius:11px; font-size:14px; font-weight:800; cursor:pointer; transition:background .2s; }
            .wt-itin__save:hover { background:#075fbc; }
            .wt-itin__notice { margin-top:13px; font-size:12px; color:var(--wt-muted); line-height:1.6; }
            .wt-itin__summary { position:sticky; top:24px; }
            .wt-itin__summary h2 { margin:0 0 16px; font-size:19px; }
            .wt-itin__summary-row { display:flex; justify-content:space-between; gap:10px; padding:13px 0; border-bottom:1px solid var(--wt-line); font-size:13px; }
            .wt-itin__summary-row span { color:var(--wt-muted); }
            .wt-itin__total { margin:22px 0 7px; color:var(--wt-blue); font-size:28px; font-weight:800; }
            .wt-itin__tip { margin:16px 0 0; padding:13px; border-radius:11px; background:#eff7ff; color:#45647d; font-size:12px; line-height:1.65; }
            .wt-itin__alert { background:#fff0ef; border:1px solid #f0c3c0; border-radius:12px; padding:14px 18px; margin-bottom:20px; color:#8d302a; }
            .wt-itin__alert ul { padding-left:18px; margin:8px 0 0; font-size:13px; }
            .wt-itin__success { background:#ebfaf2; border:1px solid #b9e6cb; color:#18633b; border-radius:12px; padding:14px 18px; margin-bottom:20px; font-size:13px; }
            .wt-itin__history { margin-top:25px; }
            .wt-itin__history h2 { margin:0 0 17px; font-size:22px; }
            .wt-itin__history-list { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
            .wt-itin__trip { background:white; border:1px solid var(--wt-line); border-radius:16px; padding:19px; min-width:0; }
            .wt-itin__trip h3 { font-size:17px; margin:9px 0; }
            .wt-itin__trip p { font-size:13px; color:var(--wt-muted); margin:6px 0; line-height:1.5; }
            .wt-itin__trip strong { font-size:16px; color:#096bc5; }
            .wt-itin__empty { padding:28px; background:white; border:1px dashed #b7cddd; border-radius:16px; color:var(--wt-muted); font-size:14px; }

            /* Alertas de presupuesto */
            .wt-budget-alert {
                background: #fef2f2;
                border: 1px solid #fecaca;
                border-radius: 12px;
                padding: 12px 14px;
                margin-top: 14px;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                color: #991b1b;
                font-size: 13px;
                line-height: 1.5;
            }
            .wt-budget-alert svg { width: 18px; height: 18px; flex-shrink: 0; color: #dc2626; margin-top: 2px; }
            .wt-budget-alert strong { font-weight: 700; }
            .wt-budget-ok {
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                border-radius: 12px;
                padding: 10px 14px;
                margin-top: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
                color: #166534;
                font-size: 13px;
            }
            .wt-budget-ok svg { width: 16px; height: 16px; color: #16a34a; flex-shrink: 0; }

            /* Notificación de borrador persistente */
            .wt-draft-bar {
                background: #f0f9ff;
                border: 1px solid #bae6fd;
                border-radius: 10px;
                padding: 10px 14px;
                margin-bottom: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 12px;
                color: #0369a1;
            }
            .wt-draft-btn {
                background: none;
                border: 0;
                color: #dc2626;
                font-weight: 600;
                cursor: pointer;
                padding: 2px 6px;
                border-radius: 4px;
            }
            .wt-draft-btn:hover { background: #fee2e2; }

            /* Lista desplegable interactiva con imágenes para País y Ciudad */
            .wt-dest-suggest {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #ffffff;
                border: 1px solid #cbd5e1;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
                max-height: 250px;
                overflow-y: auto;
                z-index: 100;
                padding: 6px;
                margin-top: 4px;
            }
            .wt-dest-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                padding: 7px 10px;
                border-radius: 8px;
                cursor: pointer;
                transition: background 0.15s;
                border: 0;
                background: none;
                width: 100%;
                text-align: left;
            }
            .wt-dest-item:hover, .wt-dest-item.is-focused {
                background: #f1f5f9;
            }
            .wt-dest-info {
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 0;
            }
            .wt-dest-thumb {
                width: 34px;
                height: 34px;
                border-radius: 6px;
                object-fit: cover;
                background: #e2e8f0;
                flex-shrink: 0;
            }
            .wt-dest-texts {
                display: flex;
                flex-direction: column;
                min-width: 0;
            }
            .wt-dest-name {
                font-size: 13px;
                font-weight: 700;
                color: #0f172a;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .wt-dest-country {
                font-size: 11px;
                color: #64748b;
            }
            .wt-dest-price {
                font-size: 12px;
                font-weight: 700;
                color: #0284c7;
                background: #e0f2fe;
                padding: 3px 8px;
                border-radius: 6px;
                white-space: nowrap;
                flex-shrink: 0;
            }

            @media (max-width:900px) { .wt-itin__grid { grid-template-columns:1fr; } .wt-itin__summary { position:static; } .wt-itin__history-list { grid-template-columns:repeat(2,minmax(0,1fr)); } }
            @media (max-width:620px) { .wt-itin { padding:16px 12px 40px; } .wt-itin__hero { padding:27px 23px; border-radius:17px; } .wt-itin__card { padding:20px 16px; } .wt-itin__fields { grid-template-columns:1fr; } .wt-itin__field--full { grid-column:auto; } .wt-itin__history-list { grid-template-columns:1fr; } .wt-itin__section-title { flex-wrap:wrap; } }
        </style>
    </x-slot:recursos>

    <div class="wt-itin">
        <div class="wt-itin__wrap">
            <header class="wt-itin__hero">
                <span class="wt-itin__kicker">WORLD TRAVEL · PLANIFICADOR INTERNACIONAL</span>
                <h1>Tu próximo viaje, a tu manera.</h1>
                <p>Organiza varias ciudades y países en un mismo itinerario, define tus fechas y calcula un presupuesto estimado en dólares estadounidenses.</p>
                <a href="{{ route('paquetes') }}">Explorar paquetes turísticos →</a>
            </header>

            @if (session('success'))
                <div class="wt-itin__success" role="status">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="wt-itin__alert" role="alert">
                    <strong>Revisa los datos de tu itinerario:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="wt-itin__grid">
                <section class="wt-itin__card" aria-labelledby="it-crear-titulo">
                    <div class="wt-itin__head">
                        <div>
                            <h2 id="it-crear-titulo">Crear itinerario</h2>
                            <p class="wt-itin__sub">Completa los datos y añade las paradas de tu viaje.</p>
                        </div>
                        <span class="wt-itin__pill">Moneda USD</span>
                    </div>

                    {{-- Barra de borrador guardado automáticamente --}}
                    <div class="wt-draft-bar" id="wt-draft-bar" hidden>
                        <span>Se restauró tu borrador no confirmado guardado localmente.</span>
                        <button type="button" class="wt-draft-btn" id="wt-draft-clear">Limpiar borrador</button>
                    </div>

                    <form action="{{ route('itinerarios.store') }}" method="POST" id="wt-itin-form">
                        @csrf
                        <div class="wt-itin__fields">
                            <div class="wt-itin__field wt-itin__field--full">
                                <label for="it-nombre">Nombre del itinerario *</label>
                                <input id="it-nombre" name="nombre" type="text" maxlength="150" value="{{ old('nombre') }}" placeholder="Ejemplo: Vacaciones por Europa" required>
                            </div>
                            <div class="wt-itin__field">
                                <label for="it-inicio">Fecha de inicio *</label>
                                <input id="it-inicio" name="fecha_inicio" type="date" value="{{ old('fecha_inicio') }}" required>
                            </div>
                            <div class="wt-itin__field">
                                <label for="it-fin">Fecha de finalización *</label>
                                <input id="it-fin" name="fecha_fin" type="date" value="{{ old('fecha_fin') }}" required>
                            </div>
                            <div class="wt-itin__field">
                                <label for="it-presupuesto">Presupuesto objetivo (US$) *</label>
                                <input id="it-presupuesto" name="presupuesto_objetivo_usd" type="number" min="0" max="9999999999.99" step="0.01" value="{{ old('presupuesto_objetivo_usd', '0.00') }}" required>
                            </div>
                            <div class="wt-itin__field">
                                <label for="it-descripcion">Descripción (opcional)</label>
                                <input id="it-descripcion" name="descripcion" type="text" maxlength="2000" value="{{ old('descripcion') }}" placeholder="¿Qué tipo de viaje quieres realizar?">
                            </div>
                        </div>

                        {{-- Alerta interactiva de presupuesto en el formulario --}}
                        <div id="it-form-budget-alert" hidden></div>

                        <div class="wt-itin__section-title">
                            <div>
                                <h3>Destinos del viaje</h3>
                                <p class="wt-itin__sub">Selecciona de nuestro catálogo o escribe tu propio destino. El costo referencial se asignará automáticamente.</p>
                            </div>
                            <button class="wt-itin__add" id="wt-itin-add" type="button">+ Agregar destino</button>
                        </div>

                        <div class="wt-itin__stops" id="wt-itin-stops">
                            @php
                                $paradas = old('destinos', [[
                                    'pais' => 'España',
                                    'ciudad' => 'Madrid',
                                    'fecha_llegada' => '',
                                    'fecha_salida' => '',
                                    'costo_estimado_usd' => '1650.00',
                                    'notas' => '',
                                ]]);
                            @endphp

                            @foreach ($paradas as $indice => $parada)
                                <div class="wt-itin__stop" data-stop>
                                    <div class="wt-itin__stop-top">
                                        <strong data-stop-title>Parada {{ $loop->iteration }}</strong>
                                        <button type="button" class="wt-itin__remove" data-remove>Eliminar</button>
                                    </div>
                                    <div class="wt-itin__fields">
                                        <div class="wt-itin__field">
                                            <label>País *</label>
                                            <input name="destinos[{{ $indice }}][pais]" data-field="pais" type="text" maxlength="120" placeholder="España" value="{{ $parada['pais'] ?? '' }}" autocomplete="off" required>
                                            <div class="wt-dest-suggest" hidden></div>
                                        </div>
                                        <div class="wt-itin__field">
                                            <label>Ciudad *</label>
                                            <input name="destinos[{{ $indice }}][ciudad]" data-field="ciudad" type="text" maxlength="120" placeholder="Madrid" value="{{ $parada['ciudad'] ?? '' }}" autocomplete="off" required>
                                            <div class="wt-dest-suggest" hidden></div>
                                        </div>
                                        <div class="wt-itin__field">
                                            <label>Fecha de llegada *</label>
                                            <input name="destinos[{{ $indice }}][fecha_llegada]" type="date" value="{{ $parada['fecha_llegada'] ?? '' }}" required>
                                        </div>
                                        <div class="wt-itin__field">
                                            <label>Fecha de salida *</label>
                                            <input name="destinos[{{ $indice }}][fecha_salida]" type="date" value="{{ $parada['fecha_salida'] ?? '' }}" required>
                                        </div>
                                        <div class="wt-itin__field">
                                            <label>Costo estimado (US$) *</label>
                                            <input name="destinos[{{ $indice }}][costo_estimado_usd]" data-costo type="number" min="0" max="9999999999.99" step="0.01" value="{{ $parada['costo_estimado_usd'] ?? '0.00' }}" required>
                                        </div>
                                        <div class="wt-itin__field">
                                            <label>Notas (opcional)</label>
                                            <input name="destinos[{{ $indice }}][notas]" type="text" maxlength="2000" placeholder="Hotel, actividades, transporte..." value="{{ $parada['notas'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="wt-itin__save">Guardar mi itinerario →</button>
                        <p class="wt-itin__notice">Tu progreso se guarda automáticamente en este navegador. Para guardar el viaje en la base de datos se requiere una sesión iniciada.</p>
                    </form>
                </section>

                <aside class="wt-itin__card wt-itin__summary" aria-labelledby="it-resumen-titulo">
                    <h2 id="it-resumen-titulo">Resumen del viaje</h2>
                    <div class="wt-itin__summary-row"><span>Destinos agregados</span><strong id="it-numero-paradas">1</strong></div>
                    <div class="wt-itin__summary-row"><span>Presupuesto objetivo</span><strong id="it-objetivo">US$ 0.00</strong></div>
                    <div class="wt-itin__summary-row"><span>Diferencia frente al objetivo</span><strong id="it-diferencia">US$ 0.00</strong></div>
                    <div class="wt-itin__total" id="it-total">US$ 0.00</div>
                    <div class="wt-itin__sub">Costo total estimado de las paradas</div>

                    {{-- Mensaje de estado dinámico de presupuesto --}}
                    <div id="it-sidebar-budget-status"></div>

                    <p class="wt-itin__tip">World Travel trabaja con USD. Los importes de este planificador son orientativos según las opciones de catálogo seleccionadas.</p>
                </aside>
            </div>

            <section class="wt-itin__history" aria-labelledby="it-guardados-titulo">
                <h2 id="it-guardados-titulo">Mis itinerarios guardados</h2>
                @forelse ($itinerarios as $itinerario)
                    @if ($loop->first)
                        <div class="wt-itin__history-list">
                    @endif
                    <article class="wt-itin__trip">
                        <span class="wt-itin__pill">{{ ucfirst($itinerario->estado) }}</span>
                        <h3>{{ $itinerario->nombre }}</h3>
                        <p>{{ $itinerario->fecha_inicio?->format('d/m/Y') }} al {{ $itinerario->fecha_fin?->format('d/m/Y') }}</p>
                        <p>{{ $itinerario->destinos->count() }} {{ $itinerario->destinos->count() === 1 ? 'destino' : 'destinos' }}</p>
                        <strong>Presupuesto: US$ {{ number_format((float) $itinerario->presupuesto_objetivo_usd, 2, '.', ',') }}</strong>
                    </article>
                    @if ($loop->last)
                        </div>
                    @endif
                @empty
                    <div class="wt-itin__empty">Todavía no tienes itinerarios guardados. Completa el formulario para planificar tu primer viaje internacional.</div>
                @endforelse
            </section>
        </div>
    </div>

    {{-- Plantilla de nueva parada con autocompletado y catálogo dinámico --}}
    <template id="wt-itin-template">
        <div class="wt-itin__stop" data-stop>
            <div class="wt-itin__stop-top">
                <strong data-stop-title>Parada __NUMBER__</strong>
                <button type="button" class="wt-itin__remove" data-remove>Eliminar</button>
            </div>
            <div class="wt-itin__fields">
                <div class="wt-itin__field">
                    <label>País *</label>
                    <input name="destinos[__INDEX__][pais]" data-field="pais" type="text" maxlength="120" placeholder="Francia" autocomplete="off" required>
                    <div class="wt-dest-suggest" hidden></div>
                </div>
                <div class="wt-itin__field">
                    <label>Ciudad *</label>
                    <input name="destinos[__INDEX__][ciudad]" data-field="ciudad" type="text" maxlength="120" placeholder="París" autocomplete="off" required>
                    <div class="wt-dest-suggest" hidden></div>
                </div>
                <div class="wt-itin__field"><label>Fecha de llegada *</label><input name="destinos[__INDEX__][fecha_llegada]" type="date" required></div>
                <div class="wt-itin__field"><label>Fecha de salida *</label><input name="destinos[__INDEX__][fecha_salida]" type="date" required></div>
                <div class="wt-itin__field"><label>Costo estimado (US$) *</label><input name="destinos[__INDEX__][costo_estimado_usd]" data-costo type="number" min="0" max="9999999999.99" step="0.01" value="0.00" required></div>
                <div class="wt-itin__field"><label>Notas (opcional)</label><input name="destinos[__INDEX__][notas]" type="text" maxlength="2000" placeholder="Hotel, actividades, transporte..."></div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('wt-itin-form');
            const container = document.getElementById('wt-itin-stops');
            const buttonAdd = document.getElementById('wt-itin-add');
            const template = document.getElementById('wt-itin-template');
            const draftBar = document.getElementById('wt-draft-bar');
            const draftClearBtn = document.getElementById('wt-draft-clear');
            const sidebarBudgetStatus = document.getElementById('it-sidebar-budget-status');
            const formBudgetAlert = document.getElementById('it-form-budget-alert');

            if (!form || !container || !buttonAdd || !template) return;

            // Catálogo inyectado desde el backend con destinos, países, imágenes y costos
            const CATALOGO = @json($catalogoDestinos ?? []);

            const dollars = amount => 'US$ ' + Number(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2, maximumFractionDigits: 2
            });
            const safeAmount = value => Math.max(0, Number(value) || 0);

            // ==========================================
            // AUTOCOMPLETADO Y SUGERENCIAS DE DESTINOS
            // ==========================================
            function setupAutocomplete(fieldInput) {
                const parent = fieldInput.closest('.wt-itin__field');
                if (!parent) return;
                const suggestBox = parent.querySelector('.wt-dest-suggest');
                if (!suggestBox) return;

                function renderSuggestions(query) {
                    const q = (query || '').trim().toLowerCase();
                    const filtered = CATALOGO.filter(item => {
                        if (!q) return true;
                        return item.ciudad.toLowerCase().includes(q) || item.pais.toLowerCase().includes(q);
                    }).slice(0, 8);

                    if (filtered.length === 0) {
                        suggestBox.innerHTML = '<div style="padding: 8px 10px; font-size: 12px; color: #94a3b8; text-align: center;">Sin coincidencias en catálogo. Puedes escribir libremente.</div>';
                        suggestBox.hidden = false;
                        return;
                    }

                    suggestBox.innerHTML = filtered.map(item => `
                        <button type="button" class="wt-dest-item" data-ciudad="${item.ciudad}" data-pais="${item.pais}" data-costo="${item.costo}">
                            <div class="wt-dest-info">
                                <img class="wt-dest-thumb" src="${item.imagen}" alt="${item.ciudad}" onerror="this.src='/icons/MachuPicchu1-Carrusel.jpg';">
                                <div class="wt-dest-texts">
                                    <span class="wt-dest-name">${item.ciudad}</span>
                                    <span class="wt-dest-country">${item.pais}</span>
                                </div>
                            </div>
                            <span class="wt-dest-price">US$ ${item.costo.toLocaleString()}</span>
                        </button>
                    `).join('');
                    suggestBox.hidden = false;
                }

                fieldInput.addEventListener('focus', () => {
                    renderSuggestions(fieldInput.value);
                });

                fieldInput.addEventListener('input', () => {
                    renderSuggestions(fieldInput.value);
                });

                suggestBox.addEventListener('click', (e) => {
                    const btn = e.target.closest('.wt-dest-item');
                    if (!btn) return;
                    e.preventDefault();

                    const stop = fieldInput.closest('[data-stop]');
                    if (!stop) return;

                    const paisInput = stop.querySelector('[data-field="pais"]');
                    const ciudadInput = stop.querySelector('[data-field="ciudad"]');
                    const costoInput = stop.querySelector('[data-costo]');

                    if (paisInput) paisInput.value = btn.dataset.pais;
                    if (ciudadInput) ciudadInput.value = btn.dataset.ciudad;
                    if (costoInput) costoInput.value = parseFloat(btn.dataset.costo || 0).toFixed(2);

                    suggestBox.hidden = true;
                    update();
                    saveDraft();
                });
            }

            // Cerrar sugerencias al hacer clic fuera
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.wt-itin__field')) {
                    document.querySelectorAll('.wt-dest-suggest').forEach(el => el.hidden = true);
                }
            });

            function attachAllAutocompletes() {
                container.querySelectorAll('input[data-field]').forEach(setupAutocomplete);
            }

            // ==========================================
            // CÁLCULO Y ALERTA DE PRESUPUESTO
            // ==========================================
            function update() {
                const stops = [...container.querySelectorAll('[data-stop]')];
                stops.forEach((stop, index) => {
                    stop.querySelector('[data-stop-title]').textContent = `Parada ${index + 1}`;
                    stop.querySelector('[data-remove]').disabled = stops.length === 1;
                    stop.querySelectorAll('[name]').forEach(input => {
                        input.name = input.name.replace(/destinos\[\d+\]/, `destinos[${index}]`);
                    });
                });

                const total = stops.reduce((sum, stop) =>
                    sum + safeAmount(stop.querySelector('[data-costo]').value), 0);
                const objetivoInput = form.elements['presupuesto_objetivo_usd'];
                const objetivo = safeAmount(objetivoInput ? objetivoInput.value : 0);

                document.getElementById('it-numero-paradas').textContent = stops.length;
                document.getElementById('it-total').textContent = dollars(total);
                document.getElementById('it-objetivo').textContent = dollars(objetivo);

                const diferencia = objetivo - total;
                const diffElem = document.getElementById('it-diferencia');

                if (objetivo > 0 && total > objetivo) {
                    const exceso = total - objetivo;
                    diffElem.textContent = 'Excede en ' + dollars(exceso);
                    diffElem.style.color = '#dc2626';

                    sidebarBudgetStatus.innerHTML = `
                        <div class="wt-budget-alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div><strong>¡Atención! Presupuesto excedido.</strong> Las paradas superan tu objetivo por ${dollars(exceso)}. Puedes ajustar los costos de las paradas o ampliar tu presupuesto.</div>
                        </div>
                    `;

                    formBudgetAlert.innerHTML = `
                        <div class="wt-budget-alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div><strong>Alerta de presupuesto:</strong> El costo total de tus paradas (${dollars(total)}) supera tu presupuesto objetivo (${dollars(objetivo)}) por ${dollars(exceso)}.</div>
                        </div>
                    `;
                    formBudgetAlert.hidden = false;
                } else if (objetivo > 0 && total <= objetivo) {
                    const disponible = objetivo - total;
                    diffElem.textContent = 'Disponible ' + dollars(disponible);
                    diffElem.style.color = '#16a34a';

                    sidebarBudgetStatus.innerHTML = `
                        <div class="wt-budget-ok">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Dentro del presupuesto (${dollars(disponible)} disponible)</span>
                        </div>
                    `;
                    formBudgetAlert.hidden = true;
                } else {
                    diffElem.textContent = dollars(0);
                    diffElem.style.color = 'inherit';
                    sidebarBudgetStatus.innerHTML = '';
                    formBudgetAlert.hidden = true;
                }

                buttonAdd.disabled = stops.length >= 12;
            }

            // ==========================================
            // AUTO-GUARDADO Y RESTAURACIÓN DEL BORRADOR
            // ==========================================
            const DRAFT_KEY = 'wt_itinerario_borrador_v1';

            function saveDraft() {
                const stops = [...container.querySelectorAll('[data-stop]')].map(stop => ({
                    pais: stop.querySelector('[data-field="pais"]')?.value || '',
                    ciudad: stop.querySelector('[data-field="ciudad"]')?.value || '',
                    fecha_llegada: stop.querySelector('input[name*="[fecha_llegada]"]')?.value || '',
                    fecha_salida: stop.querySelector('input[name*="[fecha_salida]"]')?.value || '',
                    costo_estimado_usd: stop.querySelector('[data-costo]')?.value || '0.00',
                    notas: stop.querySelector('input[name*="[notas]"]')?.value || '',
                }));

                const draft = {
                    nombre: document.getElementById('it-nombre')?.value || '',
                    fecha_inicio: document.getElementById('it-inicio')?.value || '',
                    fecha_fin: document.getElementById('it-fin')?.value || '',
                    presupuesto: document.getElementById('it-presupuesto')?.value || '0.00',
                    descripcion: document.getElementById('it-descripcion')?.value || '',
                    stops: stops,
                    timestamp: Date.now()
                };

                try {
                    localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                } catch (e) {}
            }

            function restoreDraft() {
                // Solo restaurar si no hay errores de sesión / submit previo del servidor
                @if(!$errors->any() && !old('nombre'))
                    try {
                        const saved = localStorage.getItem(DRAFT_KEY);
                        if (!saved) return;
                        const data = JSON.parse(saved);

                        // Si tiene al menos nombre o destinos guardados
                        if (data.nombre || (data.stops && data.stops.length > 0)) {
                            if (document.getElementById('it-nombre')) document.getElementById('it-nombre').value = data.nombre || '';
                            if (document.getElementById('it-inicio')) document.getElementById('it-inicio').value = data.fecha_inicio || '';
                            if (document.getElementById('it-fin')) document.getElementById('it-fin').value = data.fecha_fin || '';
                            if (document.getElementById('it-presupuesto')) document.getElementById('it-presupuesto').value = data.presupuesto || '0.00';
                            if (document.getElementById('it-descripcion')) document.getElementById('it-descripcion').value = data.descripcion || '';

                            if (Array.isArray(data.stops) && data.stops.length > 0) {
                                container.innerHTML = '';
                                data.stops.forEach((st, idx) => {
                                    const holder = document.createElement('div');
                                    holder.innerHTML = template.innerHTML
                                        .replaceAll('__INDEX__', String(idx))
                                        .replaceAll('__NUMBER__', String(idx + 1));
                                    const node = holder.firstElementChild;
                                    node.querySelector('[data-field="pais"]').value = st.pais || '';
                                    node.querySelector('[data-field="ciudad"]').value = st.ciudad || '';
                                    node.querySelector('input[name*="[fecha_llegada]"]').value = st.fecha_llegada || '';
                                    node.querySelector('input[name*="[fecha_salida]"]').value = st.fecha_salida || '';
                                    node.querySelector('[data-costo]').value = st.costo_estimado_usd || '0.00';
                                    node.querySelector('input[name*="[notas]"]').value = st.notas || '';
                                    container.appendChild(node);
                                });
                            }

                            if (draftBar) draftBar.hidden = false;
                        }
                    } catch (e) {}
                @endif
            }

            if (draftClearBtn) {
                draftClearBtn.addEventListener('click', () => {
                    localStorage.removeItem(DRAFT_KEY);
                    location.reload();
                });
            }

            form.addEventListener('submit', () => {
                localStorage.removeItem(DRAFT_KEY);
            });

            // ==========================================
            // EVENTOS Y AGREGAR PARADA
            // ==========================================
            buttonAdd.addEventListener('click', () => {
                const count = container.querySelectorAll('[data-stop]').length;
                if (count >= 12) return;
                const holder = document.createElement('div');
                holder.innerHTML = template.innerHTML
                    .replaceAll('__INDEX__', String(count))
                    .replaceAll('__NUMBER__', String(count + 1));
                const added = holder.firstElementChild;
                container.appendChild(added);

                // Setup autocompletado en los nuevos inputs
                added.querySelectorAll('input[data-field]').forEach(setupAutocomplete);
                update();
                saveDraft();
                added.querySelector('input').focus();
            });

            container.addEventListener('click', event => {
                const remove = event.target.closest('[data-remove]');
                if (!remove || container.querySelectorAll('[data-stop]').length <= 1) return;
                remove.closest('[data-stop]').remove();
                update();
                saveDraft();
            });

            form.addEventListener('input', () => {
                update();
                saveDraft();
            });

            restoreDraft();
            attachAllAutocompletes();
            update();
        });
    </script>
</x-layout>
