<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reserva::with(['cancha', 'cliente', 'usuario']);

        if ($request->has('cancha_id')) {
            $query->where('cancha_id', $request->cancha_id);
        }

        if ($request->has('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        $reservas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio')
            ->paginate(20);

        $canchas = Cancha::where('activa', true)->get();

        return view('admin.reservas.index', compact('reservas', 'canchas'));
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
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'observaciones' => 'nullable|string',
        ]);

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

        Reserva::create([
            'cancha_id' => $request->cancha_id,
            'cliente_id' => $request->cliente_id,
            'user_id' => auth()->id(),
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'estado' => 'confirmada',
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva creada exitosamente');
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
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'observaciones' => 'nullable|string',
        ]);

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
}
