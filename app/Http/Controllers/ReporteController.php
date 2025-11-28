<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Reporte de Ocupación de Canchas
     */
    public function ocupacion(Request $request)
    {
        // Fechas por defecto: últimos 30 días
        $fechaDesde = $request->get('fecha_desde', now()->subDays(30)->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $canchas = Cancha::where('activa', true)->get();

        // Obtener datos de ocupación por cancha
        $ocupacionPorCancha = [];
        $horasPorCancha = [];
        $reservasPorCancha = [];

        foreach ($canchas as $cancha) {
            $reservas = Reserva::where('cancha_id', $cancha->id)
                ->whereBetween('fecha', [$fechaDesde, $fechaHasta])
                ->where('estado', '!=', 'cancelada')
                ->get();

            $totalHoras = 0;
            $totalReservas = $reservas->count();

            foreach ($reservas as $reserva) {
                $horaInicio = Carbon::parse($reserva->hora_inicio);
                $horaFin = Carbon::parse($reserva->hora_fin);
                $horas = $horaInicio->diffInHours($horaFin);
                $totalHoras += $horas;
            }

            // Calcular horas disponibles (asumiendo 24 horas al día)
            $dias = Carbon::parse($fechaDesde)->diffInDays(Carbon::parse($fechaHasta)) + 1;
            $horasDisponibles = $dias * 24;
            $porcentajeOcupacion = $horasDisponibles > 0 ? ($totalHoras / $horasDisponibles) * 100 : 0;

            $ocupacionPorCancha[] = [
                'id' => $cancha->id,
                'nombre' => $cancha->nombre,
                'horas_reservadas' => $totalHoras,
                'horas_disponibles' => $horasDisponibles,
                'porcentaje_ocupacion' => round($porcentajeOcupacion, 2),
                'total_reservas' => $totalReservas,
                'horas_promedio' => $totalReservas > 0 ? round($totalHoras / $totalReservas, 2) : 0,
            ];

            $horasPorCancha[] = $totalHoras;
            $reservasPorCancha[] = $totalReservas;
        }

        // Análisis de horarios más solicitados
        $horariosSolicitados = Reserva::select(
                DB::raw('HOUR(hora_inicio) as hora'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('fecha', [$fechaDesde, $fechaHasta])
            ->where('estado', '!=', 'cancelada')
            ->groupBy('hora')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Análisis por día de la semana
        $reservasPorDia = Reserva::select(
                DB::raw('DAYNAME(fecha) as dia'),
                DB::raw('DAYOFWEEK(fecha) as dia_num'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('fecha', [$fechaDesde, $fechaHasta])
            ->where('estado', '!=', 'cancelada')
            ->groupBy('dia', 'dia_num')
            ->orderBy('dia_num')
            ->get();

        // Evolución temporal (últimos 7 días)
        $evolucionTemporal = [];
        $fechaInicio = Carbon::parse($fechaDesde);
        $fechaFin = Carbon::parse($fechaHasta);

        // Si el rango es mayor a 30 días, agrupar por semana, sino por día
        $diasDiferencia = $fechaInicio->diffInDays($fechaFin);

        if ($diasDiferencia > 30) {
            // Agrupar por semana
            $fechaActual = $fechaInicio->copy();
            while ($fechaActual <= $fechaFin) {
                $semanaFin = $fechaActual->copy()->endOfWeek();
                if ($semanaFin > $fechaFin) {
                    $semanaFin = $fechaFin;
                }

                $total = Reserva::whereBetween('fecha', [$fechaActual->format('Y-m-d'), $semanaFin->format('Y-m-d')])
                    ->where('estado', '!=', 'cancelada')
                    ->count();

                $evolucionTemporal[] = [
                    'periodo' => $fechaActual->format('d/m') . ' - ' . $semanaFin->format('d/m'),
                    'total' => $total
                ];

                $fechaActual->addWeek()->startOfWeek();
            }
        } else {
            // Agrupar por día
            $fechaActual = $fechaInicio->copy();
            while ($fechaActual <= $fechaFin) {
                $total = Reserva::where('fecha', $fechaActual->format('Y-m-d'))
                    ->where('estado', '!=', 'cancelada')
                    ->count();

                $evolucionTemporal[] = [
                    'periodo' => $fechaActual->format('d/m/Y'),
                    'total' => $total
                ];

                $fechaActual->addDay();
            }
        }

        return view('admin.reportes.ocupacion', compact(
            'ocupacionPorCancha',
            'horariosSolicitados',
            'reservasPorDia',
            'evolucionTemporal',
            'fechaDesde',
            'fechaHasta',
            'canchas'
        ));
    }

    /**
     * Reporte de Reservas por Período
     */
    public function reservas(Request $request)
    {
        $canchas = Cancha::where('activa', true)->get();

        // Valores por defecto si no hay filtros
        $fechaDesde = $request->get('fecha_desde', now()->subDays(30)->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        return view('admin.reportes.reservas', compact('canchas', 'fechaDesde', 'fechaHasta'));
    }

    /**
     * Exportar reservas a PDF
     */
    public function exportarReservasPdf(Request $request)
    {
        $query = Reserva::with(['cancha', 'cliente', 'usuario']);

        // Aplicar filtros
        if ($request->has('cancha_id') && $request->cancha_id != '') {
            $query->where('cancha_id', $request->cancha_id);
        }

        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->where('fecha', '<=', $request->fecha_hasta);
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
    public function exportarReservasExcel(Request $request)
    {
        $query = Reserva::with(['cancha', 'cliente', 'usuario']);

        // Aplicar filtros
        if ($request->has('cancha_id') && $request->cancha_id != '') {
            $query->where('cancha_id', $request->cancha_id);
        }

        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->where('fecha', '<=', $request->fecha_hasta);
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
                if ($horaFin->lt($horaInicio)) {
                    $horaFin->addDay();
                }
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
}

