
<x-layout>
    <section style="max-width:850px;margin:45px auto;padding:24px;">

        <h1>Finalizar pago</h1>

        <p style="color:#64748b;margin:12px 0 25px;">
            Selecciona un método de pago para tu reserva.
        </p>

        <div style="background:#fff;padding:28px;border-radius:14px;
                    border:1px solid #ddd;">

            <p style="color:#a16207;font-weight:bold;">
                SIMULACIÓN ACADÉMICA — SIN COBRO REAL
            </p>

            <h2 style="margin:20px 0;">Resumen de reserva</h2>

            <p><strong>Reserva:</strong> #{{ $reserva->id }}</p>
            <p><strong>Paquete:</strong> {{ $paquete->titulo }}</p>
            <p><strong>Destino:</strong> {{ $paquete->destino }}</p>
            <p><strong>Pasajeros:</strong> {{ $reserva->num_pasajeros }}</p>

            <hr style="margin:24px 0;">

            <h2 style="color:#0b1c27;">
                {{ $moneda }}
                {{ number_format($reserva->total, 2) }}
            </h2>

            <p>Estado: <strong>{{ ucfirst($reserva->estado) }}</strong></p>

            @if(session('error'))
                <p style="color:#b91c1c;margin-top:16px;">
                    {{ session('error') }}
                </p>
            @endif

            @if($errors->any())
                <div style="color:#b91c1c;margin-top:16px;">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if($pagoAprobado)
                <div style="padding:20px;background:#ecfdf5;
                            border-radius:10px;margin-top:25px;">
                    <h3>Pago de prueba aprobado</h3>
                    <p>La reserva quedó confirmada en la simulación.</p>

                    <a href="{{ route('pagos.comprobante', $pagoAprobado->id) }}"
                       style="display:inline-block;margin-top:15px;
                              background:#0b1c27;color:white;
                              padding:13px 20px;border-radius:8px;
                              text-decoration:none;">
                        Ver comprobante de prueba
                    </a>
                </div>

            @elseif($reserva->estado === 'pendiente')

                <h3 style="margin-top:30px;">
                    Selecciona tu método de pago
                </h3>

                <form method="POST"
                      action="{{ route('pagos.simular', $reserva->id) }}"
                      style="margin-top:20px;">

                    @csrf

                    <input type="hidden"
                           name="intento_id"
                           value="{{ $intentoId }}">

                    <div style="display:grid;gap:12px;">

                        <label style="padding:16px;border:1px solid #ddd;
                                      border-radius:8px;">
                            <input type="radio" name="metodo"
                                   value="tarjeta" checked>
                            Tarjeta de crédito o débito
                        </label>

                        <label style="padding:16px;border:1px solid #ddd;
                                      border-radius:8px;">
                            <input type="radio" name="metodo" value="yape">
                            Yape
                        </label>

                        <label style="padding:16px;border:1px solid #ddd;
                                      border-radius:8px;">
                            <input type="radio" name="metodo" value="plin">
                            Plin
                        </label>

                        <label style="padding:16px;border:1px solid #ddd;
                                      border-radius:8px;">
                            <input type="radio" name="metodo"
                                   value="transferencia">
                            Transferencia bancaria
                        </label>

                    </div>

                    <p style="font-size:13px;color:#64748b;margin-top:16px;">
                        No ingreses números de tarjetas, contraseñas
                        ni datos bancarios reales.
                    </p>

                    <div style="display:flex;flex-wrap:wrap;
                                gap:12px;margin-top:25px;">

                        <button type="submit"
                                name="resultado"
                                value="aprobado"
                                style="background:#0b1c27;color:white;
                                       border:0;padding:14px 22px;
                                       border-radius:8px;cursor:pointer;">
                            Simular pago aprobado
                        </button>

                        <button type="submit"
                                name="resultado"
                                value="rechazado"
                                style="background:white;color:#b91c1c;
                                       border:1px solid #b91c1c;
                                       padding:14px 22px;border-radius:8px;
                                       cursor:pointer;">
                            Simular pago rechazado
                        </button>

                    </div>
                </form>

            @else
                <p style="margin-top:25px;">
                    Esta reserva no está disponible para nuevos pagos.
                </p>
            @endif

            <h3 style="margin-top:35px;">Historial de pagos</h3>

            @forelse($pagos as $pago)
                <p style="margin-top:10px;">
                    Operación #{{ $pago->id }} —
                    {{ ucfirst($pago->metodo) }} —
                    {{ ucfirst($pago->estado) }}
                </p>
            @empty
                <p style="color:#64748b;margin-top:10px;">
                    Todavía no hay pagos registrados.
                </p>
            @endforelse

        </div>
    </section>
</x-layout>
