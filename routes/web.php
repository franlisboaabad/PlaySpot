<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard',[Dashboard::class,'home'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('usuarios',UserController::class)->middleware('auth');
Route::resource('roles', RoleController::class)->middleware('auth');

// Rutas de Reservas
Route::middleware('auth')->group(function () {
    Route::resource('canchas', CanchaController::class);
    Route::resource('clientes', ClienteController::class);

    // Rutas específicas de reservas ANTES del resource (para evitar conflictos)
    Route::get('reservas/calendario', [ReservaController::class, 'calendario'])->name('reservas.calendario');
    Route::get('reservas/{reserva}/ticket', [ReservaController::class, 'ticketPdf'])->name('reservas.ticket');
    Route::get('api/reservas-calendario', [ReservaController::class, 'getReservasCalendario'])->name('api.reservas.calendario');
    Route::post('api/verificar-disponibilidad', [ReservaController::class, 'verificarDisponibilidad'])->name('api.reservas.verificar');
    Route::get('api/reservas-por-fecha', [ReservaController::class, 'getReservasPorFecha'])->name('api.reservas.por-fecha');

    // Resource de reservas (debe ir después de las rutas específicas)
    Route::resource('reservas', ReservaController::class);

    // Búsqueda de clientes (AJAX)
    Route::get('api/clientes/buscar', [ClienteController::class, 'buscar'])->name('api.clientes.buscar');

    // Reportes
    Route::get('reportes/ocupacion', [ReporteController::class, 'ocupacion'])->name('reportes.ocupacion');
    Route::get('reportes/reservas', [ReporteController::class, 'reservas'])->name('reportes.reservas');
    Route::get('reportes/reservas/exportar/pdf', [ReporteController::class, 'exportarReservasPdf'])->name('reportes.exportar.reservas.pdf');
    Route::get('reportes/reservas/exportar/excel', [ReporteController::class, 'exportarReservasExcel'])->name('reportes.exportar.reservas.excel');

    // Tutoriales
    Route::get('tutoriales', [TutorialController::class, 'index'])->name('tutoriales.index');
});

require __DIR__.'/auth.php';
