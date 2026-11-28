<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservaController extends Controller
{
    /**
     * Servicios en los que se clasifican las reservas de la tabla reservas.
     *
     * @var array<string, array<string, string>>
     */
    private const SERVICIOS = [
        'vuelo' => [
            'singular' => 'Vuelo',
            'plural' => 'Vuelos',
            'icono' => 'plane',
            'descripcion' => 'Reservas de transporte aéreo registradas en World Travel.',
        ],
        'alojamiento' => [
            'singular' => 'Alojamiento',
            'plural' => 'Alojamientos',
            'icono' => 'hotel',
            'descripcion' => 'Reservas de hoteles, resorts y hospedajes.',
        ],
        'paquete' => [
            'singular' => 'Paquete',
            'plural' => 'Paquetes',
            'icono' => 'package',
            'descripcion' => 'Reservas de paquetes turísticos completos.',
        ],
        'oferta' => [
            'singular' => 'Oferta',
            'plural' => 'Ofertas',
            'icono' => 'dollar',
            'descripcion' => 'Reservas realizadas con promociones vigentes.',
        ],
    ];

    private const ESTADOS = [
        'pendiente',
        'confirmada',
        'cancelada',
    ];

    /**
     * Resumen general con acceso a las cuatro subsecciones de reservas.
     */
    public function index(): View
    {
        $estadisticas = $this->estadisticasReservas();

        return view('admin.reservas.index', [
            'totalReservas' => $estadisticas['total'],
            'pendientes' => $estadisticas['pendiente'],
            'confirmadas' => $estadisticas['confirmada'],
            'canceladas' => $estadisticas['cancelada'],
            'servicios' => $this->serviciosConConteos(),
        ]);
    }

    public function vuelos(Request $request): View
    {
        return $this->porServicio('vuelo', $request);
    }

    public function alojamientos(Request $request): View
    {
        return $this->porServicio('alojamiento', $request);
    }

    public function paquetes(Request $request): View
    {
        return $this->porServicio('paquete', $request);
    }

    public function ofertas(Request $request): View
    {
        return $this->porServicio('oferta', $request);
    }

    /**
     * Listar, buscar y filtrar las reservas de un servicio específico.
     */
    public function porServicio(string $tipoServicio, Request $request): View
    {
        $servicio = $this->servicio($tipoServicio);

        $busqueda = trim((string) $request->query('q', ''));
        $estado = (string) $request->query('estado', '');

        $reservas = $this->consultaReservas($tipoServicio, $busqueda, $estado)
            ->orderByDesc('reservas.id')
            ->paginate(10)
            ->withQueryString();

        $this->agregarAsientos($reservas);

        $estadisticas = $this->estadisticasReservas($tipoServicio);

        return view('admin.reservas.servicio', [
            'reservas' => $reservas,
            'busqueda' => $busqueda,
            'estado' => $estado,
            'totalReservas' => $estadisticas['total'],
            'pendientes' => $estadisticas['pendiente'],
            'confirmadas' => $estadisticas['confirmada'],
            'canceladas' => $estadisticas['cancelada'],
            'servicio' => $tipoServicio,
            'servicioInfo' => array_merge($servicio, [
                'ruta' => route('admin.reservas.'.$tipoServicio.'s'),
            ]),
            'servicios' => $this->serviciosConConteos(),
        ]);
    }

    /**
     * Consulta base con las relaciones reales de la BD para cada servicio.
     */
    private function consultaReservas(
        ?string $tipoServicio = null,
        string $busqueda = '',
        string $estado = ''
    ): Builder {
        $consulta = DB::table('reservas')
            ->leftJoin('users', 'users.id', '=', 'reservas.user_id')
            ->leftJoin('paquetes', 'paquetes.id', '=', 'reservas.paquete_id')
            ->leftJoin('alojamientos', 'alojamientos.id', '=', 'reservas.alojamiento_id')
            ->leftJoin('vuelos', 'vuelos.id', '=', 'reservas.vuelo_id')
            ->leftJoin('ofertas', 'ofertas.id', '=', 'reservas.oferta_id')
            ->select(
                'reservas.*',
                DB::raw('COALESCE(users.name, "Cliente Registrado") as cliente_nombre'),
                DB::raw('COALESCE(users.email, "cliente@worldtravel.com") as cliente_email'),
                DB::raw('COALESCE(paquetes.titulo, alojamientos.nombre, vuelos.codigo_vuelo, ofertas.titulo, "Viaje Turístico / Vuelo") as paquete_titulo')
            );

        if ($tipoServicio !== null) {
            $consulta->where('reservas.tipo_servicio', $tipoServicio);
        }

        // Buscar por cliente, correo, paquete, hotel, vuelo o ID.
        if ($busqueda !== '') {
            $consulta->where(function (Builder $query) use ($busqueda) {
                $query->where('users.name', 'like', "%{$busqueda}%")
                    ->orWhere('users.email', 'like', "%{$busqueda}%")
                    ->orWhere('paquetes.titulo', 'like', "%{$busqueda}%")
                    ->orWhere('alojamientos.nombre', 'like', "%{$busqueda}%")
                    ->orWhere('ofertas.titulo', 'like', "%{$busqueda}%")
                    ->orWhere('vuelos.codigo_vuelo', 'like', "%{$busqueda}%");

                if (ctype_digit($busqueda)) {
                    $query->orWhere('reservas.id', '=', (int) $busqueda);
                }
            });
        }

        if (in_array($estado, self::ESTADOS, true)) {
            $consulta->where('reservas.estado', $estado);
        }

        return $consulta;
    }

    /**
     * @return array<string, int>
     */
    private function estadisticasReservas(?string $tipoServicio = null): array
    {
        $consulta = DB::table('reservas');

        if ($tipoServicio !== null) {
            $consulta->where('tipo_servicio', $tipoServicio);
        }

        return [
            'total' => (clone $consulta)->count(),
            'pendiente' => (clone $consulta)->where('estado', 'pendiente')->count(),
            'confirmada' => (clone $consulta)->where('estado', 'confirmada')->count(),
            'cancelada' => (clone $consulta)->where('estado', 'cancelada')->count(),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function serviciosConConteos(): array
    {
        $servicios = [];

        foreach (self::SERVICIOS as $clave => $informacion) {
            $servicios[$clave] = array_merge($informacion, [
                'total' => $this->estadisticasReservas($clave)['total'],
                'ruta' => route('admin.reservas.'.$clave.'s'),
            ]);
        }

        return $servicios;
    }

    /**
     * @return array<string, string>
     */
    private function servicio(string $tipoServicio): array
    {
        abort_unless(isset(self::SERVICIOS[$tipoServicio]), 404);

        return self::SERVICIOS[$tipoServicio];
    }

    private function agregarAsientos(LengthAwarePaginator $reservas): void
    {
        $reservaIds = collect($reservas->items())->pluck('id')->all();

        $pasajerosPorReserva = DB::table('reserva_pasajeros')
            ->whereIn('reserva_id', $reservaIds)
            ->whereNotNull('asiento')
            ->where('asiento', '!=', '')
            ->get()
            ->groupBy('reserva_id');

        foreach ($reservas as $reserva) {
            $asientosLista = isset($pasajerosPorReserva[$reserva->id])
                ? $pasajerosPorReserva[$reserva->id]->pluck('asiento')->all()
                : [];

            $reserva->asientos_texto = $asientosLista !== []
                ? implode(', ', $asientosLista)
                : null;
        }
    }

    /**
     * Mostrar los detalles de una reserva.
     */
    public function show(int $reserva): View
    {
        $reservaData = DB::table('reservas')
            ->leftJoin('users', 'users.id', '=', 'reservas.user_id')
            ->leftJoin('paquetes', 'paquetes.id', '=', 'reservas.paquete_id')
            ->leftJoin('alojamientos', 'alojamientos.id', '=', 'reservas.alojamiento_id')
            ->leftJoin('vuelos', 'vuelos.id', '=', 'reservas.vuelo_id')
            ->leftJoin('ofertas', 'ofertas.id', '=', 'reservas.oferta_id')
            ->select(
                'reservas.*',
                DB::raw('COALESCE(users.name, "Cliente Registrado") as cliente_nombre'),
                DB::raw('COALESCE(users.email, "cliente@worldtravel.com") as cliente_email'),
                DB::raw('COALESCE(paquetes.titulo, alojamientos.nombre, vuelos.codigo_vuelo, ofertas.titulo, "Viaje Turístico / Vuelo") as paquete_titulo'),
                DB::raw('COALESCE(paquetes.destino, alojamientos.ubicacion, ofertas.titulo, "Destino") as paquete_destino'),
                DB::raw('COALESCE(paquetes.pais, "Mundo") as paquete_pais')
            )
            ->where('reservas.id', $reserva)
            ->first();

        abort_if(! $reservaData, 404);

        $pasajeros = DB::table('reserva_pasajeros')
            ->where('reserva_id', $reserva)
            ->orderBy('id')
            ->get();

        $tipoServicio = (string) $reservaData->tipo_servicio;
        $servicioInfo = isset(self::SERVICIOS[$tipoServicio])
            ? self::SERVICIOS[$tipoServicio]
            : [
                'singular' => ucfirst($tipoServicio),
                'plural' => ucfirst($tipoServicio),
                'descripcion' => 'Reserva registrada en World Travel.',
            ];

        return view('admin.reservas.show', [
            'reserva' => $reservaData,
            'pasajeros' => $pasajeros,
            'servicio' => $tipoServicio,
            'servicioInfo' => $servicioInfo,
        ]);
    }

    /**
     * Actualizar el estado de una reserva.
     */
    public function update(Request $request, int $reserva): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => [
                'required',
                Rule::in(self::ESTADOS),
            ],
        ]);

        $existe = DB::table('reservas')
            ->where('id', $reserva)
            ->exists();

        abort_unless($existe, 404);

        DB::table('reservas')
            ->where('id', $reserva)
            ->update([
                'estado' => $datos['estado'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.reservas.show', $reserva)
            ->with(
                'success',
                'Estado de la reserva actualizado correctamente.'
            );
    }
}
