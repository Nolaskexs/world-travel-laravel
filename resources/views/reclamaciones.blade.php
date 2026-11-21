<x-layout>
    <x-slot:titulo>World Travel | Libro de Reclamaciones</x-slot>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/css/reclamaciones.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <div class="wrap claim-container">
        @if(session('success'))
            <div class="claim-alert" role="alert" style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:1.25rem; border-radius:12px; margin-bottom:1.5rem;">
                <div style="font-weight:700; font-size:1.1rem; margin-bottom:0.25rem;">✔ Reclamación Ingresada al Sistema</div>
                <p style="margin:0;">{{ session('success') }}</p>
                @if(session('codigo_registrado'))
                    <div style="margin-top:0.6rem; padding:0.5rem 0.8rem; background:#ffffff; border-radius:8px; display:inline-block; font-weight:800; color:#0f172a; border:1px dashed #86efac;">
                        Código de seguimiento: <span style="color:#0284c7;">{{ session('codigo_registrado') }}</span>
                    </div>
                @endif
            </div>
        @endif

        @if($errors->any())
            <div class="claim-alert" role="alert" style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:1.25rem; border-radius:12px; margin-bottom:1.5rem;">
                <div style="font-weight:700; margin-bottom:0.4rem;">Por favor corrige los siguientes campos:</div>
                <ul style="margin:0; padding-left:1.2rem; font-size:0.9rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reclamaciones.store') }}" method="POST" class="claim-form" autocomplete="off">
            @csrf

            <!-- Tarjeta 1: Encabezado Legal -->
            <section class="claim-card">
                <div class="claim-header">
                    <div class="claim-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <h1>Hoja de Reclamación</h1>
                </div>
                <div class="claim-legal">
                    <p><strong>Razón Social:</strong> World Travel Perú S.A.C.</p>
                    <p><strong>RUC N°:</strong> 20609876543</p>
                    <p><strong>Dirección:</strong> Av. Santa Cruz No. 381, Miraflores, Lima, Perú.</p>
                    <p class="claim-note">*La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante el INDECOPI.</p>
                    <p class="claim-note">*El proveedor debe dar respuesta al reclamo o queja en un plazo no mayor a quince (15) días hábiles, el cual es improrrogable.</p>
                </div>
            </section>

            <!-- Tarjeta 2: Datos personales y de compra -->
            <section class="claim-card">
                <h2>Datos personales y de compra</h2>

                <div class="claim-group">
                    <label class="claim-label">SELECCIONA EL TIPO DE PERSONA *</label>
                    <div class="claim-radios">
                        <label class="claim-radio">
                            <input type="radio" name="tipo_persona" value="natural" {{ old('tipo_persona', 'natural') === 'natural' ? 'checked' : '' }} required>
                            <span>Natural</span>
                        </label>
                        <label class="claim-radio">
                            <input type="radio" name="tipo_persona" value="juridica" {{ old('tipo_persona') === 'juridica' ? 'checked' : '' }}>
                            <span>Jurídica</span>
                        </label>
                    </div>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="nombre">NOMBRES Y APELLIDOS / RAZÓN SOCIAL *</label>
                    <input class="claim-input" type="text" id="nombre" name="nombre" placeholder="Ej. Juan Pérez Quispe" value="{{ old('nombre', auth()->user()?->name) }}" required>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="documento">NÚMERO DE DOCUMENTO (DNI / RUC) *</label>
                    <input class="claim-input" type="text" id="documento" name="documento" placeholder="Ej. 74859612 o 20609876543" value="{{ old('documento', auth()->user()?->dni) }}" required>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="email">CORREO ELECTRÓNICO *</label>
                    <input class="claim-input" type="email" id="email" name="email" placeholder="correo@ejemplo.com" value="{{ old('email', auth()->user()?->email) }}" required>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="telefono">TELÉFONO CELULAR</label>
                    <input class="claim-input" type="tel" id="telefono" name="telefono" placeholder="Ej. 987654321" value="{{ old('telefono', auth()->user()?->telefono) }}">
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="pais">PAÍS DE RESIDENCIA</label>
                    <input class="claim-input" type="text" id="pais" name="pais" placeholder="Escribe aquí" value="{{ old('pais', 'Perú') }}">
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="producto">PRODUCTO O SERVICIO CONTRATADO *</label>
                    <input class="claim-input" type="text" id="producto" name="producto" placeholder="Ej. Vuelo Lima - Tokio o Paquete Machu Picchu" value="{{ old('producto') }}" required>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="reserva">NÚMERO DE LA RESERVA (SI APLICA)</label>
                    <input class="claim-input" type="text" id="reserva" name="reserva" inputmode="numeric" placeholder="Ej. 1024 (Opcional)" value="{{ old('reserva') }}">
                </div>
            </section>

            <!-- Tarjeta 3: Información sobre el reclamo -->
            <section class="claim-card">
                <h2>Información sobre el reclamo</h2>
                <p class="claim-subtitle">Indica lo qué te motivó a hacer el reclamo y lo que podemos hacer por ti</p>

                <div class="claim-group">
                    <label class="claim-label" for="motivo">SELECCIONA A QUÉ SE REFIERE TU RECLAMO *</label>
                    <div class="claim-select-wrap">
                        <select class="claim-input claim-select" id="motivo" name="motivo" required>
                            <option value="" disabled {{ old('motivo') ? '' : 'selected' }}>Elige una opción</option>
                            <option value="reclamo_vuelo" {{ old('motivo') === 'reclamo_vuelo' ? 'selected' : '' }}>Reclamo (Disconformidad con el vuelo, paquete o servicio)</option>
                            <option value="queja_atencion" {{ old('motivo') === 'queja_atencion' ? 'selected' : '' }}>Queja (Malestar en la atención al cliente)</option>
                            <option value="facturacion_cobro" {{ old('motivo') === 'facturacion_cobro' ? 'selected' : '' }}>Problemas con facturación o cobros</option>
                            <option value="reembolso_cancelacion" {{ old('motivo') === 'reembolso_cancelacion' ? 'selected' : '' }}>Reembolsos y cancelaciones</option>
                        </select>
                        <svg class="claim-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="detalle">DETALLE DEL RECLAMO *</label>
                    <textarea class="claim-input claim-textarea" id="detalle" name="detalle" rows="4" placeholder="Describe en algunas líneas el motivo de tu reclamo" required>{{ old('detalle') }}</textarea>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="pedido">PEDIDO CONCRETO REFERENTE AL RECLAMO *</label>
                    <textarea class="claim-input claim-textarea" id="pedido" name="pedido" rows="4" placeholder="Describe en pocas líneas tu requerimiento" required>{{ old('pedido') }}</textarea>
                </div>

                <div class="claim-group">
                    <label class="claim-label">SELECCIONA LA MONEDA DEL RECLAMO</label>
                    <div class="claim-radios">
                        <label class="claim-radio">
                            <input type="radio" name="moneda" value="PEN" {{ old('moneda', 'PEN') === 'PEN' ? 'checked' : '' }}>
                            <span>Soles peruanos (PEN)</span>
                        </label>
                        <label class="claim-radio">
                            <input type="radio" name="moneda" value="USD" {{ old('moneda') === 'USD' ? 'checked' : '' }}>
                            <span>Dólares (USD)</span>
                        </label>
                    </div>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="monto">MONTO RECLAMADO</label>
                    <input class="claim-input" type="number" step="0.01" min="0" id="monto" name="monto" placeholder="Ingrese solo números, decimales son permitidos" value="{{ old('monto') }}">
                </div>
            </section>

            <!-- Tarjeta 4: Envío de la respuesta -->
            <section class="claim-card">
                <h2>Envío de la respuesta</h2>
                <p class="claim-subtitle">Elige en cual formato te gustaría recibir la respuesta de tu reclamo: digital o físico</p>

                <div class="claim-group">
                    <label class="claim-label">SELECCIONA EL FORMATO DE RESPUESTA *</label>
                    <div class="claim-radios">
                        <label class="claim-radio">
                            <input type="radio" name="formato_respuesta" value="digital" checked required>
                            <span>Digital</span>
                        </label>
                        <label class="claim-radio">
                            <input type="radio" name="formato_respuesta" value="fisico">
                            <span>Físico</span>
                        </label>
                    </div>
                </div>
            </section>

            <!-- Botón de envío alineado a la derecha -->
            <div class="claim-actions">
                <button type="submit" class="claim-submit">Enviar reclamo</button>
            </div>
        </form>
    </div>
</x-layout>