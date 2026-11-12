<x-layout>
    <x-slot:titulo>World Travel | Mis itinerarios</x-slot:titulo>

    <x-slot:recursos>
        <style>
            .wt-itin { --wt-navy:#102b43; --wt-blue:#0875e9; --wt-line:#dce6f0; --wt-muted:#687d90; background:#f4f8fc; padding:38px 16px 76px; color:var(--wt-navy); }
            .wt-itin, .wt-itin * { box-sizing:border-box; }
            .wt-itin__wrap { max-width:1180px; margin:0 auto; }
            .wt-itin__hero { position:relative; padding:42px 48px; margin-bottom:26px; border-radius:24px; color:#fff; overflow:hidden; background:linear-gradient(115deg,rgba(8,31,55,.96),rgba(10,74,130,.88)),url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1600&q=85') center/cover; }
            .wt-itin__kicker { font-size:11px; font-weight:800; letter-spacing:2px; color:#aee0ff; }
            .wt-itin__hero h1 { font-size:clamp(30px,4vw,45px); margin:12px 0; color:#fff; line-height:1.15; }
            .wt-itin__hero p { max-width:610px; margin:0; line-height:1.7; color:#dceaf7; font-size:15px; }
            .wt-itin__hero a { display:inline-block; margin-top:22px; padding:11px 18px; background:#fff; border-radius:10px; color:#0e4675; font-size:13px; font-weight:800; text-decoration:none; }
            .wt-itin__grid { display:grid; grid-template-columns:minmax(0,1.7fr) minmax(280px,.8fr); gap:22px; align-items:start; }
            .wt-itin__card { background:#fff; border:1px solid var(--wt-line); border-radius:20px; padding:26px; box-shadow:0 9px 26px rgba(21,57,91,.05); }
            .wt-itin__head { display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom:20px; }
            .wt-itin__head h2 { margin:0; font-size:22px; }
            .wt-itin__sub { color:var(--wt-muted); font-size:13px; line-height:1.6; margin:5px 0 0; }
            .wt-itin__pill { background:#eaf4ff; color:#0766c9; border-radius:999px; font-weight:700; font-size:11px; padding:7px 11px; white-space:nowrap; }
            .wt-itin__fields { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
            .wt-itin__field { display:flex; flex-direction:column; gap:7px; min-width:0; }
            .wt-itin__field--full { grid-column:1/-1; }
            .wt-itin__field label { font-weight:700; font-size:12px; color:#27455d; }
            .wt-itin__field input, .wt-itin__field textarea { width:100%; min-width:0; border:1px solid #d5e1ec; border-radius:10px; padding:12px 13px; background:#f9fbfd; color:#102b43; font-size:14px; font-family:inherit; outline:none; }
            .wt-itin__field input:focus, .wt-itin__field textarea:focus { border-color:var(--wt-blue); box-shadow:0 0 0 3px rgba(8,117,233,.11); background:#fff; }
            .wt-itin__field textarea { resize:vertical; min-height:74px; }
            .wt-itin__section-title { margin:29px 0 14px; padding-top:23px; border-top:1px solid var(--wt-line); display:flex; justify-content:space-between; gap:12px; align-items:center; }
            .wt-itin__section-title h3 { font-size:17px; margin:0; }
            .wt-itin__add, .wt-itin__remove { border:1px solid #c5e1fa; background:#ecf6ff; color:#0764bd; border-radius:9px; padding:10px 13px; font-weight:700; font-size:12px; cursor:pointer; }
            .wt-itin__add:hover, .wt-itin__remove:hover { background:#dcefff; }
            .wt-itin__remove:disabled { opacity:.4; cursor:not-allowed; }
            .wt-itin__stops { display:grid; gap:13px; }
            .wt-itin__stop { background:#f8fbff; border:1px solid var(--wt-line); border-radius:15px; padding:18px; }
            .wt-itin__stop-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; gap:8px; }
            .wt-itin__stop-top strong { font-size:14px; }
            .wt-itin__save { display:block; width:100%; margin-top:24px; padding:15px 18px; background:var(--wt-blue); color:#fff; border:0; border-radius:11px; font-size:14px; font-weight:800; cursor:pointer; }
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

                        <div class="wt-itin__section-title">
                            <div>
                                <h3>Destinos del viaje</h3>
                                <p class="wt-itin__sub">Hasta 12 paradas internacionales o nacionales.</p>
                            </div>
                            <button class="wt-itin__add" id="wt-itin-add" type="button">+ Agregar destino</button>
                        </div>

                        <div class="wt-itin__stops" id="wt-itin-stops">
                            @php
                                $paradas = old('destinos', [[
                                    'pais' => '',
                                    'ciudad' => '',
                                    'fecha_llegada' => '',
                                    'fecha_salida' => '',
                                    'costo_estimado_usd' => '0.00',
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
                                            <input name="destinos[{{ $indice }}][pais]" type="text" maxlength="120" placeholder="España" value="{{ $parada['pais'] ?? '' }}" required>
                                        </div>
                                        <div class="wt-itin__field">
                                            <label>Ciudad *</label>
                                            <input name="destinos[{{ $indice }}][ciudad]" type="text" maxlength="120" placeholder="Madrid" value="{{ $parada['ciudad'] ?? '' }}" required>
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
                        <p class="wt-itin__notice">Los costos son estimaciones ingresadas por ti, no precios confirmados ni cobros. Para guardar el viaje necesitas una sesión iniciada con autenticación real.</p>
                    </form>
                </section>

                <aside class="wt-itin__card wt-itin__summary" aria-labelledby="it-resumen-titulo">
                    <h2 id="it-resumen-titulo">Resumen del viaje</h2>
                    <div class="wt-itin__summary-row"><span>Destinos agregados</span><strong id="it-numero-paradas">1</strong></div>
                    <div class="wt-itin__summary-row"><span>Presupuesto objetivo</span><strong id="it-objetivo">US$ 0.00</strong></div>
                    <div class="wt-itin__summary-row"><span>Diferencia frente al objetivo</span><strong id="it-diferencia">US$ 0.00</strong></div>
                    <div class="wt-itin__total" id="it-total">US$ 0.00</div>
                    <div class="wt-itin__sub">Costo total estimado de las paradas</div>
                    <p class="wt-itin__tip">World Travel trabaja con USD. Los importes de este planificador son presupuestos orientativos y no incluyen automáticamente vuelos, impuestos ni disponibilidad real.</p>
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

    <template id="wt-itin-template">
        <div class="wt-itin__stop" data-stop>
            <div class="wt-itin__stop-top">
                <strong data-stop-title>Parada __NUMBER__</strong>
                <button type="button" class="wt-itin__remove" data-remove>Eliminar</button>
            </div>
            <div class="wt-itin__fields">
                <div class="wt-itin__field"><label>País *</label><input name="destinos[__INDEX__][pais]" type="text" maxlength="120" placeholder="Francia" required></div>
                <div class="wt-itin__field"><label>Ciudad *</label><input name="destinos[__INDEX__][ciudad]" type="text" maxlength="120" placeholder="París" required></div>
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
            if (!form || !container || !buttonAdd || !template) return;

            const dollars = amount => 'US$ ' + Number(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2, maximumFractionDigits: 2
            });
            const safeAmount = value => Math.max(0, Number(value) || 0);

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
                const objetivo = safeAmount(form.elements['presupuesto_objetivo_usd'].value);
                document.getElementById('it-numero-paradas').textContent = stops.length;
                document.getElementById('it-total').textContent = dollars(total);
                document.getElementById('it-objetivo').textContent = dollars(objetivo);
                const diferencia = objetivo - total;
                document.getElementById('it-diferencia').textContent =
                    (diferencia < 0 ? 'Excede en ' : 'Disponible ') + dollars(Math.abs(diferencia));
                buttonAdd.disabled = stops.length >= 12;
            }

            buttonAdd.addEventListener('click', () => {
                const count = container.querySelectorAll('[data-stop]').length;
                if (count >= 12) return;
                const holder = document.createElement('div');
                holder.innerHTML = template.innerHTML
                    .replaceAll('__INDEX__', String(count))
                    .replaceAll('__NUMBER__', String(count + 1));
                const added = holder.firstElementChild;
                container.appendChild(added);
                update();
                added.querySelector('input').focus();
            });

            container.addEventListener('click', event => {
                const remove = event.target.closest('[data-remove]');
                if (!remove || container.querySelectorAll('[data-stop]').length <= 1) return;
                remove.closest('[data-stop]').remove();
                update();
            });

            form.addEventListener('input', update);
            update();
        });
    </script>
</x-layout>
