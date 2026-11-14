<x-layout>
    <x-slot:titulo>
        World Travel | Confirmar reserva
    </x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
    </x-slot:recursos>

    <main class="wt-checkout">
        <div class="checkout-container">

            <a href="{{ url()->previous() ?? route('paquetes') }}" class="checkout-volver">
                &larr; Volver
            </a>

            <div class="checkout-heading" id="checkoutHeading">
                <span class="checkout-eyebrow">WORLD TRAVEL / PROCESO PRINCIPAL</span>
                <h1>Confirma tu reserva de viaje</h1>
                <p>Completa tus datos para registrar la reserva en la base de datos de World Travel.</p>
            </div>

            <div class="checkout-pasos" id="checkoutPasos">
                <span class="paso-listo">1. Selección</span>
                <span class="paso-linea"></span>
                <span class="paso-listo">2. Cotización</span>
                <span class="paso-linea"></span>
                <span class="paso-actual">3. Confirmación y Registro</span>
            </div>

            @if ($errors->any())
                <div class="checkout-alerta checkout-alerta--error" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 14px 18px; border-radius: 10px; margin-bottom: 24px;">
                    <strong>Por favor corrige los siguientes campos:</strong>
                    <ul style="margin: 8px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="checkout-grid" id="checkoutContenido">

                <!-- INFORMACIÓN DEL VIAJERO Y DATOS DE RESERVA -->
                <section class="checkout-card">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">01</span>
                        <div>
                            <h2>Información del viajero titular</h2>
                            <p>Ingresa los datos del pasajero responsable de la reserva.</p>
                        </div>
                    </div>

                    <form id="formViajero" method="POST" action="{{ route('reservas.store') }}">
                        @csrf
                        <input type="hidden" name="paquete_id" id="inputPaqueteId" value="{{ $paqueteSeleccionado->id ?? ($paquetes->first()->id ?? 1) }}">
                        <input type="hidden" name="total" id="inputTotal" value="{{ $totalEstimado ?? 350 }}">

                        <div class="checkout-campo">
                            <label for="nombreViajero">Nombre completo *</label>
                            <input type="text"
                                name="nombre"
                                id="nombreViajero"
                                placeholder="Ej. Juan Pérez"
                                value="{{ old('nombre', $user->name ?? '') }}"
                                minlength="3"
                                maxlength="100"
                                autocomplete="name"
                                required>
                        </div>

                        <div class="checkout-campo">
                            <label for="correoViajero">Correo electrónico *</label>
                            <input type="email"
                                name="email"
                                id="correoViajero"
                                placeholder="correo@ejemplo.com"
                                value="{{ old('email', $user->email ?? '') }}"
                                autocomplete="email"
                                required>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="checkout-campo">
                                <label for="documentoViajero">Documento (DNI / Pasaporte) *</label>
                                <input type="text"
                                    name="documento"
                                    id="documentoViajero"
                                    placeholder="Ej. 74859612"
                                    value="{{ old('documento', '') }}"
                                    minlength="4"
                                    maxlength="30"
                                    required>
                            </div>

                            <div class="checkout-campo">
                                <label for="telefonoViajero">Teléfono de contacto</label>
                                <input type="tel"
                                    name="telefono"
                                    id="telefonoViajero"
                                    placeholder="Ej. 987654321"
                                    value="{{ old('telefono', '') }}"
                                    minlength="7"
                                    maxlength="20"
                                    autocomplete="tel">
                            </div>
                        </div>

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
                                <select name="num_pasajeros" id="numPasajeros" style="width: 100%; padding: 13px; border: 1px solid #dbe3eb; border-radius: 9px; background: #fff; font: inherit;">
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

                        <div class="checkout-nota" style="background: #eef8fc; border-color: #bce2f4; color: #155577; padding: 14px; border-radius: 8px; font-size: 13px; line-height: 1.5; margin-bottom: 20px;">
                            <strong>Proceso Principal de Reserva:</strong> Al confirmar, los datos se registrarán de forma real en las tablas <code>reservas</code> y <code>reserva_pasajeros</code> de MySQL. Podrás visualizar tu reserva en tu <strong>Dashboard de Cliente</strong> y el Administrador en el panel <strong>/admin/reservas</strong>.
                        </div>

                        <button type="submit" class="checkout-submit" id="btnConfirmarReserva">
                            Confirmar y Registrar Reserva
                            <span aria-hidden="true">&rarr;</span>
                        </button>
                    </form>
                </section>

                <!-- RESUMEN DEL PAQUETE / VUELO -->
                <aside class="checkout-card checkout-resumen">
                    <div class="checkout-card-header">
                        <span class="checkout-numero">02</span>
                        <div>
                            <h2>Resumen de tu viaje</h2>
                            <p>Detalles del paquete y cotización</p>
                        </div>
                    </div>

                    <div class="checkout-campo" style="margin-bottom: 16px;">
                        <label for="selectPaquete" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #5b6e7f; font-weight: 700;">Paquete o tour</label>
                        <select id="selectPaquete" style="width: 100%; padding: 11px 12px; border: 1px solid #cbd6df; border-radius: 8px; font-weight: 600; font-size: 14px; color: #0b2535; background: #fff;">
                            @foreach ($paquetes as $pkg)
                                @php
                                    $imgUrl = $pkg->imagen_url ?: 'img/viajes/roma.jpg';
                                    if (!str_starts_with($imgUrl, 'http') && !str_starts_with($imgUrl, 'img/')) {
                                        $imgUrl = 'img/viajes/' . $imgUrl;
                                    }
                                @endphp
                                <option value="{{ $pkg->id }}"
                                    data-precio="{{ $pkg->precio }}"
                                    data-destino="{{ $pkg->destino }}"
                                    data-dias="{{ $pkg->duracion_dias }}"
                                    data-imagen="{{ asset($imgUrl) }}"
                                    {{ ($paqueteSeleccionado->id ?? null) === $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->titulo }} · US$ {{ number_format($pkg->precio, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $imgPrincipal = $paqueteSeleccionado->imagen_url ?? 'img/viajes/roma.jpg';
                        if (!str_starts_with($imgPrincipal, 'http') && !str_starts_with($imgPrincipal, 'img/')) {
                            $imgPrincipal = 'img/viajes/' . $imgPrincipal;
                        }
                    @endphp

                    <img id="imagenPaquete"
                         class="checkout-imagen"
                         src="{{ asset($imgPrincipal) }}"
                         alt="{{ $paqueteSeleccionado->titulo ?? 'Paquete turístico' }}"
                         style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 14px; background: #e2e8f0;">

                    <h3 id="paqueteTitulo" style="margin: 0 0 6px; font-size: 19px; color: #0b2535; font-weight: 700;">
                        {{ $paqueteSeleccionado->titulo ?? 'Paquete Turístico' }}
                    </h3>
                    <p id="paqueteUbicacion" class="checkout-ubicacion" style="color: #6c7c8c; font-size: 13px; margin: 0 0 20px;">
                        Destino: <strong>{{ $paqueteSeleccionado->destino ?? 'Varios' }}</strong> ({{ $paqueteSeleccionado->duracion_dias ?? 5 }} días)
                    </p>

                    <div class="checkout-fila">
                        <span>Precio por persona</span>
                        <strong id="displayPrecioUnitario">US$ {{ number_format($paqueteSeleccionado->precio ?? 350, 2) }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Cantidad de viajeros</span>
                        <strong id="displayPasajeros">{{ $numPasajeros }}</strong>
                    </div>

                    <div class="checkout-fila">
                        <span>Subtotal</span>
                        <strong id="displaySubtotal">US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <div class="checkout-total">
                        <span>Total estimado</span>
                        <strong id="displayTotal">US$ {{ number_format($totalEstimado, 2) }}</strong>
                    </div>

                    <p class="checkout-aclaracion" style="margin-top: 14px; font-size: 12px; color: #8898a6;">
                        El registro se procesará con estado inicial <strong>Pendiente</strong>. Podrás verificarlo de inmediato en tu panel.
                    </p>
                </aside>
            </div>

        </div>
    </main>

    <style>
        .wt-checkout {
            min-height: 85vh;
            background: #f3f6f9;
            padding: 38px 20px 90px;
            color: #102331;
            font-family: 'Inter', Arial, sans-serif;
        }

        .wt-checkout * {
            box-sizing: border-box;
        }

        .checkout-container {
            max-width: 1120px;
            margin: 0 auto;
        }

        .checkout-volver {
            color: #236487;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
        }

        .checkout-heading {
            margin: 35px 0 28px;
        }

        .checkout-eyebrow {
            color: #287096;
            letter-spacing: 2px;
            font-size: 11px;
            font-weight: 800;
        }

        .checkout-heading h1 {
            font-size: clamp(28px, 4vw, 40px);
            margin: 10px 0;
            color: #0b2535;
        }

        .checkout-heading p {
            color: #718190;
            font-size: 15px;
        }

        .checkout-pasos {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            color: #72818e;
            font-size: 13px;
            font-weight: 600;
        }

        .paso-listo {
            color: #287096;
        }

        .paso-actual {
            color: #0b2535;
        }

        .paso-linea {
            width: 42px;
            height: 1px;
            background: #cbd6df;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            align-items: start;
            gap: 25px;
        }

        .checkout-card {
            background: #fff;
            border: 1px solid #e4eaf0;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 10px 28px rgba(20, 40, 60, .04);
        }

        .checkout-card-header {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 27px;
        }

        .checkout-numero {
            display: grid;
            place-items: center;
            min-width: 39px;
            height: 39px;
            border-radius: 11px;
            color: #236487;
            background: #eaf3f8;
            font-weight: 800;
        }

        .checkout-card h2 {
            margin: 0 0 6px;
            font-size: 21px;
        }

        .checkout-card-header p {
            margin: 0;
            font-size: 13px;
            color: #778694;
        }

        .checkout-campo {
            margin-bottom: 21px;
        }

        .checkout-campo label {
            display: block;
            font-weight: 600;
            margin-bottom: 9px;
            font-size: 13px;
        }

        .opcional {
            color: #82919d;
            font-weight: 400;
        }

        .checkout-campo input,
        .checkout-campo textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #dbe3eb;
            border-radius: 9px;
            background: #fff;
            color: #102331;
            font: inherit;
        }

        .checkout-campo input:focus,
        .checkout-campo textarea:focus,
        .checkout-campo select:focus {
            outline: none;
            border-color: #287096;
            box-shadow: 0 0 0 3px rgba(40, 112, 150, .15);
        }

        .checkout-fila {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px dashed #e4eaf0;
            font-size: 14px;
        }

        .checkout-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-top: 2px solid #0b2535;
            margin-top: 14px;
        }

        .checkout-total strong {
            font-size: 24px;
            color: #0b2535;
        }

        .checkout-submit {
            width: 100%;
            background: #102331;
            color: #fff;
            border: none;
            padding: 16px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 9px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background .2s;
        }

        .checkout-submit:hover {
            background: #1b384d;
        }

        @media (max-width: 800px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            .checkout-card {
                padding: 23px;
            }
        }
    </style>

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
                }
            }

            selectPaquete.addEventListener('change', recalcular);
            selectNumPasajeros.addEventListener('change', recalcular);
        });
    </script>
</x-layout>
