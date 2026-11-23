
<x-layout>
    <section style="max-width:700px;margin:45px auto;padding:24px;">

        <div style="background:white;border:1px solid #ddd;
                    border-radius:14px;padding:35px;">

            <!-- ENCABEZADO -->
            <div style="text-align:center;">

                <h1 style="color:#0b1c27;">
                    WORLD TRAVEL
                </h1>

                <h2 style="margin-top:20px;">
                    Comprobante de pago de prueba
                </h2>

                <p style="color:#a16207;font-weight:bold;">
                    SIMULACIÓN ACADÉMICA
                </p>

                <p>
                    Sin valor tributario — No acredita un cobro real
                </p>

            </div>

            <hr style="margin:25px 0;">

            <!-- DATOS DEL COMPROBANTE -->

            <p>
                <strong>Número de operación:</strong>
                DEMO-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}
            </p>

            <p>
                <strong>Reserva:</strong>
                #{{ $reserva->id }}
            </p>

            <p>
                <strong>Cliente:</strong>
                {{ $cliente->name ?? auth()->user()->name }}
            </p>

            <p>
                <strong>Paquete:</strong>
                {{ $paquete->titulo }}
            </p>

            <p>
                <strong>Destino:</strong>
                {{ $paquete->destino }}
            </p>

            <p>
                <strong>Método de pago:</strong>
                {{ ucfirst($pago->metodo) }}
            </p>

            <p>
                <strong>Fecha:</strong>
                {{ $pago->verificado_en?->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong>Resultado:</strong>
                Aprobado en simulación
            </p>

            <hr style="margin:25px 0;">

            <!-- TOTAL -->

            <h2 style="text-align:right;color:#0b1c27;">
                Total: {{ $pago->moneda }}
                {{ number_format($pago->monto, 2) }}
            </h2>

            <p style="text-align:center;color:#64748b;
                      font-size:13px;margin-top:25px;">
                Documento generado por el simulador académico
                de World Travel.
                No es boleta ni factura electrónica.
            </p>

            <!-- BOTONES -->

            <div class="acciones-comprobante"
                 style="display:flex;gap:12px;
                        justify-content:center;
                        margin-top:25px;flex-wrap:wrap;">

                <button
                    type="button"
                    onclick="window.print()"
                    style="background:#0b1c27;
                           color:white;
                           border:0;
                           padding:12px 20px;
                           border-radius:8px;
                           cursor:pointer;">
                    Imprimir / Guardar como PDF
                </button>

                <!-- REGRESAR SEGÚN EL TIPO DE USUARIO -->

                @if(request()->routeIs('admin.facturacion.comprobante'))

                    <a href="{{ route('admin.facturacion.index') }}"
                       style="padding:12px 20px;
                              color:#0b1c27;
                              text-decoration:none;">
                        Volver a Facturación
                    </a>

                @else

                    <a href="{{ route('pagos.show', $reserva->id) }}"
                       style="padding:12px 20px;
                              color:#0b1c27;
                              text-decoration:none;">
                        Volver a mi reserva
                    </a>

                    <a href="{{ auth()->user()?->esAdmin() ? route('admin.reservas.show', $reserva->id) : route('dashboard') }}"
                       style="padding:12px 20px;
                              background:#0b1c27;
                              color:#fff;
                              border-radius:8px;
                              text-decoration:none;
                              font-weight:600;">
                        Ver en {{ auth()->user()?->esAdmin() ? 'Panel Admin' : 'Mis Viajes (Dashboard)' }} &rarr;
                    </a>

                @endif

            </div>

        </div>
    </section>

    <!-- ESTILOS PARA IMPRESIÓN -->

    <style>
        @media print {

            .acciones-comprobante,
            header,
            nav,
            footer {
                display: none !important;
            }

            body {
                background: white !important;
            }

            section {
                margin: 0 auto !important;
            }
        }
    </style>

</x-layout>
