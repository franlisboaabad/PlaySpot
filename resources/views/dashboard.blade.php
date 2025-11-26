@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <!-- Info Boxes -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalReservas }}</h3>
                    <p>Total Reservas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('reservas.index') }}" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $reservasHoy }}</h3>
                    <p>Reservas Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <a href="{{ route('reservas.index') }}?fecha={{ date('Y-m-d') }}" class="small-box-footer">
                    Ver reservas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalClientes }}</h3>
                    <p>Total Clientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('clientes.index') }}" class="small-box-footer">
                    Ver clientes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalCanchas }}</h3>
                    <p>Canchas Activas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-futbol"></i>
                </div>
                <a href="{{ route('canchas.index') }}" class="small-box-footer">
                    Ver canchas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <!-- Estadísticas de Reservas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $reservasConfirmadas }}</h3>
                    <p>Reservas Confirmadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $reservasPendientes }}</h3>
                    <p>Reservas Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $reservasSemana }}</h3>
                    <p>Reservas Esta Semana</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $reservasMes }}</h3>
                    <p>Reservas Este Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Próximas Reservas -->
        <div class="col-12 col-md-8 mb-3 mb-md-0">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check mr-1"></i>
                        Próximas Reservas
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Cancha</th>
                                    <th>Cliente</th>
                                    <th class="d-none d-md-table-cell">Teléfono</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                        <tbody>
                            @forelse($proximasReservas as $reserva)
                                <tr>
                                    <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
                                    <td>{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</td>
                                    <td>{{ $reserva->cancha->nombre }}</td>
                                    <td>{{ $reserva->cliente->nombre }}</td>
                                    <td class="d-none d-md-table-cell">{{ $reserva->cliente->telefono }}</td>
                                    <td>
                                        @if($reserva->estado == 'confirmada')
                                            <span class="badge badge-success">Confirmada</span>
                                        @elseif($reserva->estado == 'pendiente')
                                            <span class="badge badge-warning">Pendiente</span>
                                        @elseif($reserva->estado == 'cancelada')
                                            <span class="badge badge-danger">Cancelada</span>
                                        @else
                                            <span class="badge badge-info">Completada</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay reservas próximas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('reservas.index') }}" class="btn btn-sm btn-primary">
                        Ver todas las reservas
                    </a>
                </div>
            </div>
        </div>

        <!-- Reservas por Cancha (últimos 7 días) -->
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Reservas por Cancha
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info">Últimos 7 días</span>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($reservasPorCancha as $item)
                        <div class="progress-group">
                            <span class="progress-text">{{ $item->nombre }}</span>
                            <span class="float-right"><b>{{ $item->total }}</b> reservas</span>
                            <div class="progress progress-sm">
                                @php
                                    $max = $reservasPorCancha->max('total');
                                    $percentage = $max > 0 ? ($item->total / $max) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay reservas en los últimos 7 días</p>
                    @endforelse
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-1"></i>
                        Accesos Rápidos
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('reservas.create') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-plus"></i> Nueva Reserva
                    </a>
                    <a href="{{ route('reservas.calendario') }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-calendar"></i> Ver Calendario
                    </a>
                    <a href="{{ route('clientes.create') }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-user-plus"></i> Nuevo Cliente
                    </a>
                    <a href="{{ route('canchas.create') }}" class="btn btn-warning btn-block">
                        <i class="fas fa-futbol"></i> Nueva Cancha
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Dashboard cargado'); </script>
@stop
