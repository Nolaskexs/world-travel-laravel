<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
use App\Models\Oferta;
use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\ReservaPasajero;
use App\Models\Vuelo;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservaClienteController extends Controller
{
    /**
     * Mostrar el formulario del proceso de reserva de paquetes (Checkout).
     */
    public function create(Request $request): View
    {
        $paquetes = Paquete::where('activo', true)->orderBy('titulo')->get();

        $paqueteId = $request->query('paquete_id');
        $paqueteSeleccionado = $paqueteId
            ? $paquetes->firstWhere('id', (int) $paqueteId)
            : null;

        // Si no se especificó o no se encontró, tomar el primer paquete disponible
        if (! $paqueteSeleccionado) {
            $paqueteSeleccionado = $paquetes->first();
        }

        $fechaViaje = $request->query('fecha_viaje', $request->query('salida', now()->addDays(14)->format('Y-m-d')));
        $numPasajeros = max(1, (int) $request->query('num_pasajeros', $request->query('adults', 1)));
        $asientos = (string) $request->query('asientos', '');
        $precioUnitario = (float) ($paqueteSeleccionado?->precio ?? 350.00);
        $totalEstimado = (float) $request->query('total', $precioUnitario * $numPasajeros);

        $user = Auth::user();

        return view('confirmar_reserva_paquetes', compact(
            'paquetes',
            'paqueteSeleccionado',
            'fechaViaje',
            'numPasajeros',
            'asientos',
            'totalEstimado',
            'user'
        ));
    }

    /**
     * Registrar la reserva de paquete y sus pasajeros en la base de datos (MySQL).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:150',
            'telefono' => 'nullable|string|max:30',
            'documento' => 'required|string|min:4|max:30',
            'paquete_id' => 'required|exists:paquetes,id',
            'fecha_viaje' => 'required|date',
            'num_pasajeros' => 'required|integer|min:1|max:10',
            'total' => 'required|numeric|min:0',
        ]);

        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para reservar.');
        }

        if (! in_array(Auth::user()->rol, ['cliente', 'admin'])) {
            return back()
                ->withErrors([
                    'cuenta' => 'Debes iniciar sesión como cliente o administrador para reservar.',
                ])
                ->withInput();
        }

        $userId = Auth::id();

        $paquete = Paquete::where('activo', true)
            ->findOrFail($validated['paquete_id']);

        $totalCalculado = (
            (int) round(((float) $paquete->precio) * 100)
            * (int) $validated['num_pasajeros']
        ) / 100;

        // 1. Guardar en la tabla reservas
        $reserva = Reserva::create([
            'user_id' => $userId,
            'paquete_id' => $validated['paquete_id'],
            'tipo_servicio' => 'paquete',
            'fecha_viaje' => $validated['fecha_viaje'],
            'num_pasajeros' => $validated['num_pasajeros'],
            'total' => $totalCalculado,
            'estado' => 'pendiente',
        ]);

        // Procesar asientos si fueron seleccionados en el mapa del avión
        $asientosInput = $request->input('asientos', '');
        $asientosLista = [];
        if (! empty($asientosInput)) {
            $asientosLista = array_values(array_filter(array_map('trim', explode(',', (string) $asientosInput))));
        }

        // Si no se eligieron suficientes asientos en el mapa, auto-asignar asientos reales disponibles
        if (count($asientosLista) < (int) $validated['num_pasajeros']) {
            $asientosOcupados = ReservaPasajero::whereNotNull('asiento')->where('asiento', '!=', '')->pluck('asiento')->all();
            $filas = range(10, 35);
            $letras = ['A', 'B', 'C', 'D', 'E', 'F'];
            foreach ($filas as $fila) {
                foreach ($letras as $letra) {
                    $codigo = $fila.$letra;
                    if (! in_array($codigo, $asientosOcupados, true) && ! in_array($codigo, $asientosLista, true)) {
                        $asientosLista[] = $codigo;
                        if (count($asientosLista) >= (int) $validated['num_pasajeros']) {
                            break 2;
                        }
                    }
                }
            }
        }

        // 2. Guardar en la tabla reserva_pasajeros (Pasajero titular)
        ReservaPasajero::create([
            'reserva_id' => $reserva->id,
            'nombre' => $validated['nombre'],
            'documento' => $validated['documento'],
            'asiento' => $asientosLista[0] ?? '14A',
        ]);

        // Pasajeros acompañantes si num_pasajeros > 1
        for ($i = 2; $i <= $validated['num_pasajeros']; $i++) {
            ReservaPasajero::create([
                'reserva_id' => $reserva->id,
                'nombre' => "Pasajero acompañante {$i} ({$validated['nombre']})",
                'documento' => 'DNI-'.rand(10000000, 99999999),
                'asiento' => $asientosLista[$i - 1] ?? ('14'.chr(64 + $i)),
            ]);
        }

        $asientosTexto = implode(', ', array_slice($asientosLista, 0, (int) $validated['num_pasajeros']));

        if (Auth::user()?->esAdmin()) {
            return redirect()
                ->route('admin.reservas.show', $reserva->id)
                ->with('success', "¡Reserva #{$reserva->id} registrada exitosamente en la base de datos! Asientos asignados: {$asientosTexto}.");
        }

        return redirect()
            ->route('pagos.show', $reserva->id)
            ->with(
                'success',
                "Reserva #{$reserva->id} registrada correctamente. Asientos asignados: {$asientosTexto}. Continúa con el pago."
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Reserva independiente de alojamientos
    |--------------------------------------------------------------------------
    */

    public function confirmarAlojamiento(Request $request): View
    {
        $filtros = $request->validate([
            'alojamiento' => ['nullable', 'string', 'max:80'],
            'entrada' => ['nullable', 'date'],
            'salida' => ['nullable', 'date', 'after_or_equal:entrada'],
            'huespedes' => ['nullable', 'integer', 'min:1', 'max:4'],
        ]);

        $alojamiento = Alojamiento::where('activo', true)
            ->where('codigo', $filtros['alojamiento'] ?? '')
            ->first();

        if (! $alojamiento) {
            $alojamiento = Alojamiento::where('activo', true)->orderBy('id')->firstOrFail();
        }

        $entrada = $filtros['entrada'] ?? now()->addDays(14)->toDateString();
        $salida = $filtros['salida'] ?? Carbon::parse($entrada)->addDay()->toDateString();
        $noches = max(1, Carbon::parse($entrada)->diffInDays(Carbon::parse($salida)));
        $huespedes = (int) ($filtros['huespedes'] ?? 2);

        $precioNocheUsd = $this->precioEnUsd((float) $alojamiento->precio, $alojamiento->moneda_simbolo);
        $totalEstimado = round($precioNocheUsd * $noches, 2);
        $user = Auth::user();

        return view('confirmar_reserva_alojamientos', compact(
            'alojamiento',
            'entrada',
            'salida',
            'noches',
            'huespedes',
            'precioNocheUsd',
            'totalEstimado',
            'user'
        ));
    }

    public function guardarAlojamiento(Request $request): RedirectResponse
    {
        $respuestaCuenta = $this->validarCuenta();
        if ($respuestaCuenta) {
            return $respuestaCuenta;
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'documento' => ['required', 'string', 'min:4', 'max:30'],
            'alojamiento_id' => ['required', 'exists:alojamientos,id'],
            'fecha_entrada' => ['required', 'date'],
            'fecha_salida' => ['required', 'date', 'after:fecha_entrada'],
            'num_pasajeros' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        $alojamiento = Alojamiento::where('activo', true)->findOrFail($validated['alojamiento_id']);
        $noches = Carbon::parse($validated['fecha_entrada'])->diffInDays(Carbon::parse($validated['fecha_salida']));
        $precioNocheUsd = $this->precioEnUsd((float) $alojamiento->precio, $alojamiento->moneda_simbolo);
        $total = round($precioNocheUsd * $noches, 2);

        $reserva = $this->registrarServicio([
            'tipo_servicio' => 'alojamiento',
            'alojamiento_id' => $alojamiento->id,
            'fecha_viaje' => $validated['fecha_entrada'],
            'fecha_entrada' => $validated['fecha_entrada'],
            'fecha_salida' => $validated['fecha_salida'],
            'noches' => $noches,
            'moneda' => $alojamiento->moneda_simbolo,
            'cotizacion' => $alojamiento->precio,
            'num_pasajeros' => $validated['num_pasajeros'],
            'total' => $total,
            'detalle_servicio' => $alojamiento->nombre,
            'detalle_snapshot' => [
                'codigo' => $alojamiento->codigo,
                'nombre' => $alojamiento->nombre,
                'tipo' => $alojamiento->tipo,
                'ubicacion' => $alojamiento->ubicacion,
                'habitacion' => $alojamiento->habitacion,
                'precio_noche' => $alojamiento->precio,
                'moneda' => $alojamiento->moneda_simbolo,
                'precio_noche_usd' => $precioNocheUsd,
                'noches' => $noches,
                'servicios' => $alojamiento->servicios ?? [],
            ],
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'documento' => $validated['documento'],
            'asientos' => $validated['asientos'] ?? $request->input('asientos'),
        ]);

        return redirect()
            ->route('reservas.exito', $reserva)
            ->with('success', '¡Reserva de alojamiento realizada correctamente!');
    }

    /*
    |--------------------------------------------------------------------------
    | Reserva independiente de vuelos
    |--------------------------------------------------------------------------
    */

    public function confirmarVuelo(Request $request): View
    {
        $filtros = $request->validate([
            'vuelo_id' => ['nullable', 'integer'],
            'origen' => ['nullable', 'string', 'max:100'],
            'destino' => ['nullable', 'string', 'max:100'],
            'origin' => ['nullable', 'string', 'max:100'],
            'destination' => ['nullable', 'string', 'max:100'],
            'origin_iata' => ['nullable', 'string', 'max:10'],
            'destination_iata' => ['nullable', 'string', 'max:10'],
            'codigo_vuelo' => ['nullable', 'string', 'max:30'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'impuestos' => ['nullable', 'numeric', 'min:0'],
            'fecha_viaje' => ['nullable', 'date'],
            'adultos' => ['nullable', 'integer', 'min:0', 'max:9'],
            'menores' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infantes' => ['nullable', 'integer', 'min:0', 'max:9'],
            'asientos' => ['nullable', 'string', 'max:255'],
        ]);

        $vuelo = ! empty($filtros['vuelo_id'])
            ? Vuelo::with(['origen', 'destino', 'escalas'])->where('activo', true)->find($filtros['vuelo_id'])
            : null;

        $origen = $vuelo?->origen?->nombre ?: ($filtros['origen'] ?? $filtros['origin'] ?? 'Lima');
        $destino = $vuelo?->destino?->nombre ?: ($filtros['destino'] ?? $filtros['destination'] ?? 'Tokio');
        $origenIata = $vuelo?->origen?->codigo_iata ?: ($filtros['origin_iata'] ?? 'LIM');
        $destinoIata = $vuelo?->destino?->codigo_iata ?: ($filtros['destination_iata'] ?? 'HND');
        $codigoVuelo = $vuelo?->codigo_vuelo ?: ($filtros['codigo_vuelo'] ?? 'WT-8183');

        $precioAdulto = $vuelo ? (float) $vuelo->precio_adulto : (float) ($filtros['precio'] ?? 389);
        $precioMenor = $vuelo && $vuelo->precio_menor !== null ? (float) $vuelo->precio_menor : $precioAdulto;
        $precioInfante = $vuelo ? (float) ($vuelo->precio_infante ?? 0) : 0;
        $impuestos = $vuelo ? (float) $vuelo->impuestos_tasas : (float) ($filtros['impuestos'] ?? 0);

        $adultos = (int) ($filtros['adultos'] ?? 1);
        $menores = (int) ($filtros['menores'] ?? 0);
        $infantes = (int) ($filtros['infantes'] ?? 0);
        $numPasajeros = max(1, $adultos + $menores + $infantes);
        $asientos = $filtros['asientos'] ?? '';
        $fechaViaje = $filtros['fecha_viaje'] ?? now()->addDays(14)->toDateString();
        $totalEstimado = $this->calcularTotalVuelo($precioAdulto, $precioMenor, $precioInfante, $impuestos, $adultos, $menores, $infantes);
        $user = Auth::user();

        return view('confirmar_reserva_vuelos', compact(
            'vuelo',
            'origen',
            'destino',
            'origenIata',
            'destinoIata',
            'codigoVuelo',
            'precioAdulto',
            'precioMenor',
            'precioInfante',
            'impuestos',
            'adultos',
            'menores',
            'infantes',
            'numPasajeros',
            'asientos',
            'fechaViaje',
            'totalEstimado',
            'user'
        ));
    }

    public function guardarVuelo(Request $request): RedirectResponse
    {
        $respuestaCuenta = $this->validarCuenta();
        if ($respuestaCuenta) {
            return $respuestaCuenta;
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'documento' => ['required', 'string', 'min:4', 'max:30'],
            'tipo_documento' => ['nullable', 'in:DNI,Pasaporte'],
            'vuelo_id' => ['nullable', 'exists:vuelos,id'],
            'origen' => ['required', 'string', 'max:100'],
            'destino' => ['required', 'string', 'max:100'],
            'origin_iata' => ['nullable', 'string', 'max:10'],
            'destination_iata' => ['nullable', 'string', 'max:10'],
            'codigo_vuelo' => ['required', 'string', 'max:30'],
            'precio_adulto' => ['required', 'numeric', 'min:0'],
            'impuestos' => ['nullable', 'numeric', 'min:0'],
            'fecha_viaje' => ['required', 'date'],
            'adultos' => ['required', 'integer', 'min:0', 'max:9'],
            'menores' => ['required', 'integer', 'min:0', 'max:9'],
            'infantes' => ['required', 'integer', 'min:0', 'max:9'],
            'asientos' => ['nullable', 'string', 'max:255'],
        ]);

        $vuelo = ! empty($validated['vuelo_id'])
            ? Vuelo::with(['origen', 'destino', 'escalas'])->find($validated['vuelo_id'])
            : null;

        $precioAdulto = $vuelo ? (float) $vuelo->precio_adulto : (float) $validated['precio_adulto'];
        $precioMenor = $vuelo && $vuelo->precio_menor !== null ? (float) $vuelo->precio_menor : $precioAdulto;
        $precioInfante = $vuelo ? (float) ($vuelo->precio_infante ?? 0) : 0;
        $impuestos = $vuelo ? (float) $vuelo->impuestos_tasas : (float) ($validated['impuestos'] ?? 0);
        $adultos = (int) $validated['adultos'];
        $menores = (int) $validated['menores'];
        $infantes = (int) $validated['infantes'];
        $numPasajeros = max(1, $adultos + $menores + $infantes);
        $total = $this->calcularTotalVuelo($precioAdulto, $precioMenor, $precioInfante, $impuestos, $adultos, $menores, $infantes);

        $asientos = trim((string) ($validated['asientos'] ?? ''));
        $primerAsiento = $asientos !== '' ? explode(',', $asientos)[0] : null;

        $reserva = $this->registrarServicio([
            'tipo_servicio' => 'vuelo',
            'vuelo_id' => $vuelo?->id,
            'fecha_viaje' => $validated['fecha_viaje'],
            'moneda' => $vuelo?->moneda ?? 'USD',
            'cotizacion' => $precioAdulto,
            'num_pasajeros' => $numPasajeros,
            'total' => $total,
            'asientos' => $asientos !== '' ? $asientos : null,
            'detalle_servicio' => "{$validated['codigo_vuelo']} · {$validated['origen']} → {$validated['destino']}",
            'detalle_snapshot' => [
                'codigo_vuelo' => $validated['codigo_vuelo'],
                'aerolinea' => $vuelo?->aerolinea ?? 'World Travel',
                'origen' => $validated['origen'],
                'destino' => $validated['destino'],
                'origen_iata' => $validated['origin_iata'] ?? null,
                'destino_iata' => $validated['destination_iata'] ?? null,
                'duracion' => $vuelo?->duracion_formateada ?? null,
                'escalas' => $vuelo?->cantidad_escalas ?? 0,
                'adultos' => $adultos,
                'menores' => $menores,
                'infantes' => $infantes,
                'precio_adulto' => $precioAdulto,
                'precio_menor' => $precioMenor,
                'precio_infante' => $precioInfante,
                'impuestos' => $impuestos,
                'asientos' => $asientos !== '' ? $asientos : null,
                'tipo_documento' => $validated['tipo_documento'] ?? 'DNI',
            ],
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'documento' => $validated['documento'],
            'asiento' => $primerAsiento,
        ]);

        return redirect()
            ->route('reservas.exito', $reserva)
            ->with('success', '¡Reserva de vuelo realizada correctamente!');
    }

    /*
    |--------------------------------------------------------------------------
    | Reserva independiente de ofertas
    |--------------------------------------------------------------------------
    */

    public function confirmarOferta(Request $request): View
    {
        $filtros = $request->validate([
            'oferta_id' => ['nullable', 'integer'],
            'fecha_viaje' => ['nullable', 'date'],
            'num_pasajeros' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $hoy = now()->toDateString();
        $oferta = Oferta::with('paquete')
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->whereHas('paquete', fn ($q) => $q->where('activo', true));

        if (! empty($filtros['oferta_id'])) {
            $oferta->where('id', $filtros['oferta_id']);
        }

        $oferta = $oferta->orderByDesc('descuento_porcentaje')->firstOrFail();
        $paquete = $oferta->paquete;
        $asientos = (string) $request->query('asientos', '');
        $precioOriginal = (float) $paquete->precio;
        $precioFinal = round($precioOriginal * (1 - $oferta->descuento_porcentaje / 100), 2);
        $numPasajeros = (int) ($filtros['num_pasajeros'] ?? 1);
        $totalEstimado = round($precioFinal * $numPasajeros, 2);
        $fechaViaje = $filtros['fecha_viaje'] ?? now()->addDays(14)->toDateString();
        $user = Auth::user();

        return view('confirmar_reserva_ofertas', compact(
            'oferta',
            'paquete',
            'precioOriginal',
            'precioFinal',
            'numPasajeros',
            'asientos',
            'totalEstimado',
            'fechaViaje',
            'user'
        ));
    }

    public function guardarOferta(Request $request): RedirectResponse
    {
        $respuestaCuenta = $this->validarCuenta();
        if ($respuestaCuenta) {
            return $respuestaCuenta;
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'documento' => ['required', 'string', 'min:4', 'max:30'],
            'oferta_id' => ['required', 'exists:ofertas,id'],
            'fecha_viaje' => ['required', 'date'],
            'num_pasajeros' => ['required', 'integer', 'min:1', 'max:10'],
            'asientos' => ['nullable', 'string', 'max:255'],
        ]);

        $hoy = now()->toDateString();
        $oferta = Oferta::with('paquete')
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->findOrFail($validated['oferta_id']);

        abort_unless($oferta->paquete?->activo, 404);

        $paquete = $oferta->paquete;
        $precioOriginal = (float) $paquete->precio;
        $precioFinal = round($precioOriginal * (1 - $oferta->descuento_porcentaje / 100), 2);
        $total = round($precioFinal * (int) $validated['num_pasajeros'], 2);

        $reserva = $this->registrarServicio([
            'tipo_servicio' => 'oferta',
            'oferta_id' => $oferta->id,
            'fecha_viaje' => $validated['fecha_viaje'],
            'moneda' => $paquete->moneda ?: 'USD',
            'cotizacion' => $precioFinal,
            'num_pasajeros' => $validated['num_pasajeros'],
            'total' => $total,
            'detalle_servicio' => $oferta->titulo,
            'detalle_snapshot' => [
                'oferta_id' => $oferta->id,
                'titulo' => $oferta->titulo,
                'descuento_porcentaje' => $oferta->descuento_porcentaje,
                'precio_original' => $precioOriginal,
                'precio_final' => $precioFinal,
                'paquete_titulo' => $paquete->titulo,
                'destino' => $paquete->destino,
                'pais' => $paquete->pais,
                'duracion_dias' => $paquete->duracion_dias,
                'incluye_hotel' => (bool) $paquete->incluye_hotel,
                'incluye_vuelo' => (bool) $paquete->incluye_vuelo,
                'incluye_tour' => (bool) $paquete->incluye_tour,
                'fecha_fin' => $oferta->fecha_fin->toDateString(),
            ],
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'documento' => $validated['documento'],
            'asientos' => $validated['asientos'] ?? $request->input('asientos'),
        ]);

        return redirect()
            ->route('reservas.exito', $reserva)
            ->with('success', '¡Reserva de oferta realizada correctamente!');
    }

    /*
    |--------------------------------------------------------------------------
    | Confirmación visible después de registrar cualquier servicio
    |--------------------------------------------------------------------------
    */

    public function exito(Reserva $reserva): View
    {
        $user = Auth::user();

        abort_unless(
            $user && ($user->esAdmin() || (int) $reserva->user_id === (int) $user->id),
            403
        );

        $reserva->loadMissing(['vuelo', 'alojamiento', 'oferta', 'paquete', 'pasajeros']);

        return view('reserva-exito', compact('reserva'));
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers compartidos por los flujos independientes
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica que haya una cuenta de cliente o administrador válida.
     */
    private function validarCuenta(): ?RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para reservar.');
        }

        if (! in_array(Auth::user()->rol, ['cliente', 'admin'])) {
            return back()
                ->withErrors([
                    'cuenta' => 'Debes iniciar sesión como cliente o administrador para reservar.',
                ])
                ->withInput();
        }

        return null;
    }

    /**
     * Convierte precios en soles al USD usado por el sistema para los totales.
     */
    private function precioEnUsd(float $precio, ?string $monedaSimbolo): float
    {
        return $monedaSimbolo === 'S/' ? round($precio / 3.51, 2) : $precio;
    }

    private function calcularTotalVuelo(
        float $precioAdulto,
        float $precioMenor,
        float $precioInfante,
        float $impuestos,
        int $adultos,
        int $menores,
        int $infantes
    ): float {
        return round(
            $precioAdulto * $adultos
            + $precioMenor * $menores
            + $precioInfante * $infantes
            + $impuestos * ($adultos + $menores + $infantes),
            2
        );
    }

    /**
     * Escribe la reserva y el pasajero titular de forma atómica.
     *
     * @param  array<string, mixed>  $data
     */
    private function registrarServicio(array $data): Reserva
    {
        return DB::transaction(function () use ($data): Reserva {
            $numPax = max(1, (int) ($data['num_pasajeros'] ?? 1));

            // Procesar asientos si fueron proporcionados o si el servicio incluye vuelo
            $asientosInput = $data['asientos'] ?? '';
            $asientosLista = [];
            if (! empty($asientosInput)) {
                $asientosLista = array_values(array_filter(array_map('trim', explode(',', (string) $asientosInput))));
            }

            $incluyeVuelo = ($data['tipo_servicio'] === 'vuelo')
                || ! empty($data['vuelo_id'])
                || ! empty($data['detalle_snapshot']['incluye_vuelo']);

            if ($incluyeVuelo && count($asientosLista) < $numPax) {
                $asientosOcupados = ReservaPasajero::whereNotNull('asiento')->where('asiento', '!=', '')->pluck('asiento')->all();
                $filas = range(10, 35);
                $letras = ['A', 'B', 'C', 'D', 'E', 'F'];
                foreach ($filas as $fila) {
                    foreach ($letras as $letra) {
                        $codigo = $fila.$letra;
                        if (! in_array($codigo, $asientosOcupados, true) && ! in_array($codigo, $asientosLista, true)) {
                            $asientosLista[] = $codigo;
                            if (count($asientosLista) >= $numPax) {
                                break 2;
                            }
                        }
                    }
                }
            }

            $asientosTexto = ! empty($asientosLista) ? implode(', ', array_slice($asientosLista, 0, $numPax)) : ($data['asientos'] ?? null);

            $reserva = Reserva::create([
                'user_id' => Auth::id(),
                'paquete_id' => null,
                'tipo_servicio' => $data['tipo_servicio'],
                'vuelo_id' => $data['vuelo_id'] ?? null,
                'alojamiento_id' => $data['alojamiento_id'] ?? null,
                'oferta_id' => $data['oferta_id'] ?? null,
                'fecha_viaje' => $data['fecha_viaje'],
                'fecha_entrada' => $data['fecha_entrada'] ?? null,
                'fecha_salida' => $data['fecha_salida'] ?? null,
                'noches' => $data['noches'] ?? null,
                'moneda' => $data['moneda'] ?? 'USD',
                'cotizacion' => $data['cotizacion'] ?? null,
                'num_pasajeros' => $numPax,
                'total' => $data['total'],
                'detalle_servicio' => $data['detalle_servicio'],
                'detalle_snapshot' => $data['detalle_snapshot'] + [
                    'contacto' => [
                        'nombre' => $data['nombre'],
                        'email' => $data['email'],
                        'telefono' => $data['telefono'] ?? null,
                        'documento' => $data['documento'],
                    ],
                ],
                'asientos' => $asientosTexto,
                'estado' => 'pendiente',
            ]);

            // Pasajero titular
            ReservaPasajero::create([
                'reserva_id' => $reserva->id,
                'nombre' => $data['nombre'],
                'documento' => $data['documento'],
                'asiento' => $asientosLista[0] ?? ($data['asiento'] ?? null),
            ]);

            // Pasajeros acompañantes si num_pasajeros > 1
            for ($i = 2; $i <= $numPax; $i++) {
                ReservaPasajero::create([
                    'reserva_id' => $reserva->id,
                    'nombre' => "Pasajero acompañante {$i} ({$data['nombre']})",
                    'documento' => 'DNI-'.rand(10000000, 99999999),
                    'asiento' => $asientosLista[$i - 1] ?? null,
                ]);
            }

            return $reserva;
        });
    }
}
