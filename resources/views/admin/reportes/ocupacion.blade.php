@extends('adminlte::page')

@section('title', 'Reporte de Ocupación')

@section('content_header')
    <h1>Reporte de Ocupación de Canchas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('reportes.ocupacion') }}" class="form-inline">
                <div class="form-group mr-2">
                    <label class="mr-2">Desde:</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ $fechaDesde }}" required>
                </div>
                <div class="form-group mr-2">
                    <label class="mr-2">Hasta:</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ $fechaHasta }}" required>
                </div>
                <div class="form-group mr-2">
                    <button type="submit" class="btn btn-primary btn-sm">Generar Reporte</button>
                </div>
                <div class="form-group">
                    <a href="{{ route('reportes.ocupacion', ['fecha_desde' => now()->subDays(7)->format('Y-m-d'), 'fecha_hasta' => now()->format('Y-m-d')]) }}" class="btn btn-secondary btn-sm">Últimos 7 días</a>
                    <a href="{{ route('reportes.ocupacion', ['fecha_desde' => now()->startOfMonth()->format('Y-m-d'), 'fecha_hasta' => now()->format('Y-m-d')]) }}" class="btn btn-secondary btn-sm">Este mes</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <!-- Resumen por Cancha -->
            <div class="row mb-4">
                <div class="col-12">
                    <h4>Resumen por Cancha</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Cancha</th>
                                    <th>Horas Reservadas</th>
                                    <th>Horas Disponibles</th>
                                    <th>% Ocupación</th>
                                    <th>Total Reservas</th>
                                    <th>Promedio (horas/reserva)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ocupacionPorCancha as $item)
                                    <tr>
                                        <td><strong>{{ $item['nombre'] }}</strong></td>
                                        <td>{{ $item['horas_reservadas'] }} hrs</td>
                                        <td>{{ $item['horas_disponibles'] }} hrs</td>
                                        <td>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar {{ $item['porcentaje_ocupacion'] > 70 ? 'bg-success' : ($item['porcentaje_ocupacion'] > 40 ? 'bg-warning' : 'bg-danger') }}"
                                                     role="progressbar"
                                                     style="width: {{ min($item['porcentaje_ocupacion'], 100) }}%"
                                                     aria-valuenow="{{ $item['porcentaje_ocupacion'] }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    {{ number_format($item['porcentaje_ocupacion'], 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item['total_reservas'] }}</td>
                                        <td>{{ $item['horas_promedio'] }} hrs</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay datos para el período seleccionado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
                <!-- Gráfico de Horas por Cancha -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Horas Reservadas por Cancha</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartHorasCancha" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Reservas por Cancha -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Cantidad de Reservas por Cancha</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartReservasCancha" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Horarios Más Solicitados -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Horarios Más Solicitados</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartHorarios" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Reservas por Día de la Semana -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Reservas por Día de la Semana</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartDiasSemana" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Evolución Temporal -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Evolución de Reservas en el Tiempo</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartEvolucion" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Datos para los gráficos
        const ocupacionPorCancha = @json($ocupacionPorCancha);
        const horariosSolicitados = @json($horariosSolicitados);
        const reservasPorDia = @json($reservasPorDia);
        const evolucionTemporal = @json($evolucionTemporal);

        // Gráfico de Horas por Cancha
        const ctxHoras = document.getElementById('chartHorasCancha').getContext('2d');
        new Chart(ctxHoras, {
            type: 'bar',
            data: {
                labels: ocupacionPorCancha.map(item => item.nombre),
                datasets: [{
                    label: 'Horas Reservadas',
                    data: ocupacionPorCancha.map(item => item.horas_reservadas),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Horas'
                        }
                    }
                }
            }
        });

        // Gráfico de Reservas por Cancha
        const ctxReservas = document.getElementById('chartReservasCancha').getContext('2d');
        new Chart(ctxReservas, {
            type: 'doughnut',
            data: {
                labels: ocupacionPorCancha.map(item => item.nombre),
                datasets: [{
                    data: ocupacionPorCancha.map(item => item.total_reservas),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Gráfico de Horarios Más Solicitados
        const ctxHorarios = document.getElementById('chartHorarios').getContext('2d');
        new Chart(ctxHorarios, {
            type: 'bar',
            data: {
                labels: horariosSolicitados.map(item => item.hora + ':00'),
                datasets: [{
                    label: 'Reservas',
                    data: horariosSolicitados.map(item => item.total),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Reservas por Día de la Semana
        const ctxDias = document.getElementById('chartDiasSemana').getContext('2d');
        new Chart(ctxDias, {
            type: 'line',
            data: {
                labels: reservasPorDia.map(item => item.dia),
                datasets: [{
                    label: 'Reservas',
                    data: reservasPorDia.map(item => item.total),
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Evolución Temporal
        const ctxEvolucion = document.getElementById('chartEvolucion').getContext('2d');
        new Chart(ctxEvolucion, {
            type: 'line',
            data: {
                labels: evolucionTemporal.map(item => item.periodo),
                datasets: [{
                    label: 'Reservas',
                    data: evolucionTemporal.map(item => item.total),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@stop

