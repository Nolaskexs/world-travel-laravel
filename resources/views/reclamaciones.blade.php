<x-layout>
    <x-slot:titulo>World Travel | Libro de Reclamaciones</x-slot>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/css/reclamaciones.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <div class="wrap claim-container">
        @if(session('success'))
            <div class="claim-alert" role="alert">
                {{ session('success') }}
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
                            <input type="radio" name="tipo_persona" value="natural" checked required>
                            <span>Natural</span>
                        </label>
                        <label class="claim-radio">
                            <input type="radio" name="tipo_persona" value="juridica">
                            <span>Jurídica</span>
                        </label>
                    </div>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="telefono">TELÉFONO CELULAR</label>
                    <input class="claim-input" type="tel" id="telefono" name="telefono" placeholder="Escribe aquí">
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="pais">PAÍS DE RESIDENCIA</label>
                    <input class="claim-input" type="text" id="pais" name="pais" placeholder="Escribe aquí" value="Perú">
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="producto">PRODUCTO O SERVICIO CONTRATADO *</label>
                    <input class="claim-input" type="text" id="producto" name="producto" placeholder="Escribe aquí" required>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="reserva">NÚMERO DE LA RESERVA (SI APLICA)</label>
                    <input class="claim-input" type="text" id="reserva" name="reserva" inputmode="numeric" pattern="[0-9]*" placeholder="Escribe aquí, sólo números">
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
                            <option value="" disabled selected>Elige</option>
                            <option value="reclamo_vuelo">Reclamo (Disconformidad con el vuelo, paquete o servicio)</option>
                            <option value="queja_atencion">Queja (Malestar en la atención al cliente)</option>
                            <option value="facturacion_cobro">Problemas con facturación o cobros</option>
                            <option value="reembolso_cancelacion">Reembolsos y cancelaciones</option>
                        </select>
                        <svg class="claim-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="detalle">DETALLE DEL RECLAMO *</label>
                    <textarea class="claim-input claim-textarea" id="detalle" name="detalle" rows="4" placeholder="Describe en algunas líneas el motivo de tu reclamo" required></textarea>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="pedido">PEDIDO CONCRETO REFERENTE AL RECLAMO *</label>
                    <textarea class="claim-input claim-textarea" id="pedido" name="pedido" rows="4" placeholder="Describe en pocas líneas tu requerimiento" required></textarea>
                </div>

                <div class="claim-group">
                    <label class="claim-label">SELECCIONA LA MONEDA DEL RECLAMO</label>
                    <div class="claim-radios">
                        <label class="claim-radio">
                            <input type="radio" name="moneda" value="PEN" checked>
                            <span>Soles peruanos (PEN)</span>
                        </label>
                        <label class="claim-radio">
                            <input type="radio" name="moneda" value="USD">
                            <span>Dólares (USD)</span>
                        </label>
                    </div>
                </div>

                <div class="claim-group">
                    <label class="claim-label" for="monto">MONTO RECLAMADO</label>
                    <input class="claim-input" type="number" step="0.01" min="0" id="monto" name="monto" placeholder="Ingrese solo números, decimales son permitidos">
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