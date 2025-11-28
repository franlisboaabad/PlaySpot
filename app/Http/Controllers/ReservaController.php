<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reservas = Reserva::with(['cancha', 'cliente', 'usuario'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio')
            ->get();

        return view('admin.reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $canchas = Cancha::where('activa', true)->get();
        $clientes = Cliente::orderBy('nombre')->get();

        $fechaPreseleccionada = $request->get('fecha');
        $canchaPreseleccionada = $request->get('cancha_id');
        $horaInicioPreseleccionada = $request->get('hora_inicio');
        $horaFinPreseleccionada = $request->get('hora_fin');

        return view('admin.reservas.create', compact('canchas', 'clientes', 'fechaPreseleccionada', 'canchaPreseleccionada', 'horaInicioPreseleccionada', 'horaFinPreseleccionada'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'observaciones' => 'nullable|string',
        ]);

        // Validar que hora_fin > hora_inicio (maneja reservas que cruzan medianoche)
        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = Carbon::parse($request->hora_fin);

        // Si hora_fin es menor que hora_inicio, asumimos que cruza la medianoche
        if ($horaFin->lt($horaInicio)) {
            $horaFin->addDay();
        }

        // Calcular diferencia en horas
        $diferenciaHoras = $horaInicio->diffInHours($horaFin);

        // Validar que haya al menos 1 hora de diferencia
        if ($diferenciaHoras < 1) {
            return back()->withErrors([
                'hora_fin' => 'La hora de fin debe ser al menos 1 hora después de la hora de inicio.'
            ])->withInput();
        }

        // Validar que la reserva no exceda 24 horas
        if ($diferenciaHoras > 24) {
            return back()->withErrors([
                'hora_fin' => 'La reserva no puede exceder 24 horas de duración.'
            ])->withInput();
        }

        // Validar que la fecha no sea pasada
        $fechaReserva = Carbon::parse($request->fecha)->startOfDay();
        $fechaHoy = Carbon::today();

        if ($fechaReserva->lt($fechaHoy)) {
            return back()->withErrors([
                'fecha' => 'No se pueden crear reservas para fechas pasadas.'
            ])->withInput();
        }

        // Si es hoy, validar que la hora sea futura
        if ($fechaReserva->eq($fechaHoy)) {
            $horaInicio = Carbon::parse($request->fecha . ' ' . $request->hora_inicio);
            $ahora = Carbon::now();

            if ($horaInicio->lte($ahora)) {
                return back()->withErrors([
                    'hora_inicio' => 'No se pueden crear reservas para horas pasadas del día de hoy.'
                ])->withInput();
            }
        }

        // Validar disponibilidad
        if (!Reserva::validarDisponibilidad(
            $request->cancha_id,
            $request->fecha,
            $request->hora_inicio,
            $request->hora_fin
        )) {
            return back()->withErrors([
                'horario' => 'El horario seleccionado ya está ocupado. Por favor, seleccione otro horario.'
            ])->withInput();
        }

        $reserva = Reserva::create([
            'cancha_id' => $request->cancha_id,
            'cliente_id' => $request->cliente_id,
            'user_id' => auth()->id(),
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'estado' => 'confirmada',
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('reservas.show', $reserva)
            ->with('success', 'Reserva creada exitosamente. Puedes descargar el ticket PDF para enviarlo al cliente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        $reserva->load(['cancha', 'cliente', 'usuario']);
        return view('admin.reservas.show', compact('reserva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        $canchas = Cancha::where('activa', true)->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('admin.reservas.edit', compact('reserva', 'canchas', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'observaciones' => 'nullable|string',
        ]);

        // Validar que hora_fin > hora_inicio (maneja reservas que cruzan medianoche)
        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = Carbon::parse($request->hora_fin);

        // Si hora_fin es menor que hora_inicio, asumimos que cruza la medianoche
        // En ese caso, agregamos 24 horas a hora_fin para la comparación
        if ($horaFin->lt($horaInicio)) {
            $horaFin->addDay();
        }

        // Calcular diferencia en horas
        $diferenciaHoras = $horaInicio->diffInHours($horaFin);

        // Validar que haya al menos 1 hora de diferencia
        if ($diferenciaHoras < 1) {
            return back()->withErrors([
                'hora_fin' => 'La hora de fin debe ser al menos 1 hora después de la hora de inicio.'
            ])->withInput();
        }

        // Validar que la reserva no exceda 24 horas (si cruza medianoche, máximo hasta el día siguiente)
        if ($diferenciaHoras > 24) {
            return back()->withErrors([
                'hora_fin' => 'La reserva no puede exceder 24 horas de duración.'
            ])->withInput();
        }

        // Validar disponibilidad (excluyendo la reserva actual)
        if (!Reserva::validarDisponibilidad(
            $request->cancha_id,
            $request->fecha,
            $request->hora_inicio,
            $request->hora_fin,
            $reserva->id
        )) {
            return back()->withErrors([
                'horario' => 'El horario seleccionado ya está ocupado. Por favor, seleccione otro horario.'
            ])->withInput();
        }

        $reserva->update($request->all());

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->delete();

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva eliminada exitosamente');
    }

    /**
     * Calendario de reservas
     */
    public function calendario()
    {
        $canchas = Cancha::where('activa', true)->get();
        return view('admin.reservas.calendario', compact('canchas'));
    }

    /**
     * API: Obtener reservas para el calendario
     */
    public function getReservasCalendario(Request $request)
    {
        $canchaId = $request->get('cancha', 0);

        $query = Reserva::with(['cancha', 'cliente'])
            ->where('estado', '!=', 'cancelada');

        if ($canchaId > 0) {
            $query->where('cancha_id', $canchaId);
        }

        $reservas = $query->get()->map(function($reserva) {
            $colores = [
                1 => '#3788d8', // Azul
                2 => '#28a745', // Verde
                3 => '#ffc107', // Amarillo
            ];

            return [
                'id' => $reserva->id,
                'title' => $reserva->cancha->nombre . ' - ' . $reserva->cliente->nombre,
                'start' => $reserva->fecha->format('Y-m-d') . 'T' . $reserva->hora_inicio,
                'end' => $reserva->fecha->format('Y-m-d') . 'T' . $reserva->hora_fin,
                'color' => $colores[$reserva->cancha_id] ?? '#6c757d',
                'extendedProps' => [
                    'cancha' => $reserva->cancha->nombre,
                    'cliente' => $reserva->cliente->nombre,
                    'telefono' => $reserva->cliente->telefono,
                    'estado' => $reserva->estado,
                ]
            ];
        });

        return response()->json($reservas);
    }

    /**
     * Verificar disponibilidad (AJAX)
     */
    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
        ]);

        $disponible = Reserva::validarDisponibilidad(
            $request->cancha_id,
            $request->fecha,
            $request->hora_inicio,
            $request->hora_fin,
            $request->reserva_id ?? null
        );

        return response()->json([
            'disponible' => $disponible
        ]);
    }

    /**
     * Obtener reservas de una cancha en una fecha específica (AJAX)
     */
    public function getReservasPorFecha(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date',
        ]);

        $reservas = Reserva::where('cancha_id', $request->cancha_id)
            ->where('fecha', $request->fecha)
            ->where('estado', '!=', 'cancelada')
            ->with('cliente')
            ->orderBy('hora_inicio')
            ->get()
            ->map(function($reserva) {
                return [
                    'id' => $reserva->id,
                    'hora_inicio' => $reserva->hora_inicio,
                    'hora_fin' => $reserva->hora_fin,
                    'cliente' => $reserva->cliente->nombre,
                    'telefono' => $reserva->cliente->telefono,
                ];
            });

        return response()->json($reservas);
    }

    /**
     * Exportar reservas a PDF
     */
    public function exportarPdf(Request $request)
    {
        $query = Reserva::with(['cancha', 'cliente', 'usuario']);

        // Aplicar los mismos filtros que en index
        if ($request->has('cancha_id') && $request->cancha_id != '') {
            $query->where('cancha_id', $request->cancha_id);
        }

        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->has('fecha') && $request->fecha != '' && !$request->has('fecha_desde')) {
            $query->where('fecha', $request->fecha);
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('cliente') && $request->cliente != '') {
            $query->whereHas('cliente', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cliente . '%');
            });
        }

        $reservas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio')
            ->get();

        $filtros = [
            'cancha' => $request->cancha_id ? Cancha::find($request->cancha_id)?->nombre : 'Todas',
            'fecha_desde' => $request->fecha_desde ?? 'N/A',
            'fecha_hasta' => $request->fecha_hasta ?? 'N/A',
            'estado' => $request->estado ?? 'Todos',
        ];

        return view('admin.reservas.exportar-pdf', compact('reservas', 'filtros'));
    }

    /**
     * Exportar reservas a Excel/CSV
     */
    public function exportarExcel(Request $request)
    {
        $query = Reserva::with(['cancha', 'cliente', 'usuario']);

        // Aplicar los mismos filtros que en index
        if ($request->has('cancha_id') && $request->cancha_id != '') {
            $query->where('cancha_id', $request->cancha_id);
        }

        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->has('fecha') && $request->fecha != '' && !$request->has('fecha_desde')) {
            $query->where('fecha', $request->fecha);
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('cliente') && $request->cliente != '') {
            $query->whereHas('cliente', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cliente . '%');
            });
        }

        $reservas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio')
            ->get();

        $filename = 'reservas_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reservas) {
            $file = fopen('php://output', 'w');

            // BOM para Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($file, [
                'ID',
                'Fecha',
                'Hora Inicio',
                'Hora Fin',
                'Cancha',
                'Cliente',
                'Teléfono',
                'Email',
                'Estado',
                'Usuario',
                'Duración (horas)',
                'Observaciones'
            ], ';');

            // Datos
            foreach ($reservas as $reserva) {
                $horaInicio = Carbon::parse($reserva->hora_inicio);
                $horaFin = Carbon::parse($reserva->hora_fin);
                $duracion = $horaInicio->diffInHours($horaFin);

                fputcsv($file, [
                    $reserva->id,
                    $reserva->fecha->format('d/m/Y'),
                    $reserva->hora_inicio,
                    $reserva->hora_fin,
                    $reserva->cancha->nombre,
                    $reserva->cliente->nombre,
                    $reserva->cliente->telefono,
                    $reserva->cliente->email ?? '',
                    ucfirst($reserva->estado),
                    $reserva->usuario->name ?? '',
                    $duracion,
                    $reserva->observaciones ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generar ticket PDF de la reserva
     */
    public function ticketPdf(Reserva $reserva)
    {
        $reserva->load(['cancha', 'cliente', 'usuario']);

        // Forzar descarga del PDF
        $html = view('admin.reservas.ticket-pdf', compact('reserva'))->render();
        $filename = 'ticket_reserva_' . str_pad($reserva->id, 6, '0', STR_PAD_LEFT) . '.html';

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
