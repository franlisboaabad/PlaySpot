<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cancha;
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
}

