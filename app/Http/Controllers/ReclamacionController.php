<?php

namespace App\Http\Controllers;

use App\Models\Reclamacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReclamacionController extends Controller
{
    /**
     * Muestra el formulario oficial del Libro de Reclamaciones.
     */
    public function create(): View
    {
        return view('reclamaciones');
    }

    /**
     * Registra una nueva hoja de reclamación en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_persona' => ['required', 'in:natural,juridica'],
            'nombre' => ['required', 'string', 'max:150'],
            'tipo_documento' => ['nullable', 'string', 'in:DNI,RUC,CE,Pasaporte'],
            'documento' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:40'],
            'pais' => ['nullable', 'string', 'max:80'],
            'producto' => ['required', 'string', 'max:180'],
            'reserva' => ['nullable', 'string', 'max:50'],
            'motivo' => ['required', 'string', 'in:reclamo_vuelo,queja_atencion,facturacion_cobro,reembolso_cancelacion'],
            'detalle' => ['required', 'string', 'min:10'],
            'pedido' => ['required', 'string', 'min:5'],
            'moneda' => ['required', 'in:PEN,USD'],
            'monto' => ['nullable', 'numeric', 'min:0'],
            'formato_respuesta' => ['required', 'in:digital,fisico'],
        ], [
            'nombre.required' => 'Por favor ingresa tu nombre completo o razón social.',
            'documento.required' => 'Ingresa tu número de documento de identidad (DNI o RUC).',
            'email.required' => 'El correo electrónico es indispensable para enviarte la respuesta.',
            'producto.required' => 'Indica el producto, vuelo o paquete contratado.',
            'motivo.required' => 'Selecciona el motivo de tu reclamo.',
            'detalle.required' => 'Describe los detalles de los hechos ocurridos.',
            'pedido.required' => 'Indica tu pedido concreto para dar solución.',
        ]);

        // Determinar tipo: Queja (atención al cliente) vs Reclamo (producto o servicio)
        $tipo = ($validated['motivo'] === 'queja_atencion') ? 'queja' : 'reclamo';

        // Determinar tipo_documento según tipo_persona si no se especificó
        $tipoDocumento = $validated['tipo_documento'] ?? ($validated['tipo_persona'] === 'juridica' ? 'RUC' : 'DNI');

        $reclamacion = Reclamacion::create([
            'codigo' => Reclamacion::generarCodigo(),
            'user_id' => auth()->id(),
            'tipo_persona' => $validated['tipo_persona'],
            'nombre' => $validated['nombre'],
            'tipo_documento' => $tipoDocumento,
            'documento' => $validated['documento'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'pais' => $validated['pais'] ?? 'Perú',
            'tipo_bien' => 'servicio',
            'producto' => $validated['producto'],
            'reserva' => $validated['reserva'] ?? null,
            'moneda' => $validated['moneda'],
            'monto' => $validated['monto'] ?? null,
            'tipo' => $tipo,
            'motivo' => $validated['motivo'],
            'detalle' => $validated['detalle'],
            'pedido' => $validated['pedido'],
            'formato_respuesta' => $validated['formato_respuesta'],
            'estado' => 'pendiente',
        ]);

        return back()->with([
            'success' => "¡Tu {$reclamacion->tipo} ha sido registrado exitosamente con el código {$reclamacion->codigo}! Te enviaremos la respuesta formal a {$reclamacion->email} en un plazo máximo de 15 días hábiles conforme a ley.",
            'codigo_registrado' => $reclamacion->codigo,
        ]);
    }
}
