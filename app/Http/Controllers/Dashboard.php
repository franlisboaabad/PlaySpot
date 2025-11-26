<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    public function home()
    {
        // Estadísticas generales
        $totalReservas = Reserva::count();
        $totalClientes = Cliente::count();
        $totalCanchas = Cancha::where('activa', true)->count();
        
        // Reservas del día
        $reservasHoy = Reserva::where('fecha', today())
            ->where('estado', '!=', 'cancelada')
            ->count();
        
        // Reservas por estado
        $reservasConfirmadas = Reserva::where('estado', 'confirmada')->count();
        $reservasPendientes = Reserva::where('estado', 'pendiente')->count();
        $reservasCanceladas = Reserva::where('estado', 'cancelada')->count();
        
        // Reservas de esta semana
        $reservasSemana = Reserva::whereBetween('fecha', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->where('estado', '!=', 'cancelada')->count();
        
        // Reservas del mes
        $reservasMes = Reserva::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->where('estado', '!=', 'cancelada')
            ->count();
        
        // Próximas reservas (hoy y mañana)
        $proximasReservas = Reserva::with(['cancha', 'cliente'])
            ->where('fecha', '>=', today())
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->limit(10)
            ->get();
        
        // Reservas por cancha (últimos 7 días)
        $reservasPorCancha = Reserva::select('canchas.nombre', DB::raw('count(*) as total'))
            ->join('canchas', 'reservas.cancha_id', '=', 'canchas.id')
            ->where('reservas.fecha', '>=', now()->subDays(7))
            ->where('reservas.estado', '!=', 'cancelada')
            ->groupBy('canchas.nombre')
            ->get();

        return view('dashboard', compact(
            'totalReservas',
            'totalClientes',
            'totalCanchas',
            'reservasHoy',
            'reservasConfirmadas',
            'reservasPendientes',
            'reservasCanceladas',
            'reservasSemana',
            'reservasMes',
            'proximasReservas',
            'reservasPorCancha'
        ));
    }
}
