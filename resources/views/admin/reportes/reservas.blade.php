@extends('adminlte::page')

@section('title', 'Reporte de Reservas')

@section('content_header')
    <h1><i class="fas fa-file-alt"></i> Reporte de Reservas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros del Reporte</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.reservas') }}" id="formReporte">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Cancha:</label>
                            <select name="cancha_id" class="form-control">
                                <option value="">Todas las canchas</option>
                                @foreach($canchas as $cancha)
                                    <option value="{{ $cancha->id }}" {{ request('cancha_id') == $cancha->id ? 'selected' : '' }}>
                                        {{ $cancha->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Fecha Desde:</label>
                            <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde', $fechaDesde) }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Fecha Hasta:</label>
                            <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta', $fechaHasta) }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Estado:</label>
                            <select name="estado" class="form-control">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Cliente:</label>
                            <input type="text" name="cliente" class="form-control" placeholder="Buscar por nombre..." value="{{ request('cliente') }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Generar Reporte
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <a href="{{ route('reportes.reservas') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-redo"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(request()->hasAny(['cancha_id', 'fecha_desde', 'fecha_hasta', 'estado', 'cliente']))
        @php
            $query = Reserva::with(['cancha', 'cliente', 'usuario']);

            if (request('cancha_id')) {
                $query->where('cancha_id', request('cancha_id'));
            }

            if (request('fecha_desde')) {
                $query->where('fecha', '>=', request('fecha_desde'));
            }

            if (request('fecha_hasta')) {
                $query->where('fecha', '<=', request('fecha_hasta'));
            }

            if (request('estado')) {
                $query->where('estado', request('estado'));
            }

            if (request('cliente')) {
                $query->whereHas('cliente', function($q) {
                    $q->where('nombre', 'like', '%' . request('cliente') . '%');
                });
            }

            $reservas = $query->orderBy('fecha', 'desc')
                ->orderBy('hora_inicio')
                ->get();
        @endphp

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resultados del Reporte</h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $reservas->count() }} reserva(s) encontrada(s)</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <a href="{{ route('reportes.exportar.reservas.pdf', request()->query()) }}" class="btn btn-danger btn-md-inline" target="_blank">
                            <i class="fas fa-file-pdf"></i> Exportar a PDF
                        </a>
                        <a href="{{ route('reportes.exportar.reservas.excel', request()->query()) }}" class="btn btn-success btn-md-inline">
                            <i class="fas fa-file-excel"></i> Exportar a Excel
                        </a>
                    </div>
                </div>

                @if($reservas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Cancha</th>
                                    <th>Cliente</th>
                                    <th class="d-none d-md-table-cell">Teléfono</th>
                                    <th>Estado</th>
                                    <th>Duración</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservas as $reserva)
                                    @php
                                        $horaInicio = \Carbon\Carbon::parse($reserva->hora_inicio);
                                        $horaFin = \Carbon\Carbon::parse($reserva->hora_fin);
                                        if ($horaFin->lt($horaInicio)) {
                                            $horaFin->addDay();
                                        }
                                        $duracion = $horaInicio->diffInHours($horaFin);
                                    @endphp
                                    <tr>
                                        <td>{{ $reserva->id }}</td>
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
                                        <td>{{ $duracion }} hr(s)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No se encontraron reservas con los filtros seleccionados.
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>Selecciona los filtros y haz clic en "Generar Reporte" para ver los resultados</h4>
                    <p class="mb-0">Puedes filtrar por cancha, rango de fechas, estado y cliente.</p>
                </div>
            </div>
        </div>
    @endif
@stop

@section('js')
    <script>
        // Botones rápidos de fecha
        document.addEventListener('DOMContentLoaded', function() {
            // Botón "Últimos 7 días"
            const btnUltimos7 = document.createElement('button');
            btnUltimos7.type = 'button';
            btnUltimos7.className = 'btn btn-sm btn-outline-secondary mt-2';
            btnUltimos7.innerHTML = '<i class="fas fa-calendar-week"></i> Últimos 7 días';
            btnUltimos7.onclick = function() {
                const desde = new Date();
                desde.setDate(desde.getDate() - 7);
                document.querySelector('input[name="fecha_desde"]').value = desde.toISOString().split('T')[0];
                document.querySelector('input[name="fecha_hasta"]').value = new Date().toISOString().split('T')[0];
            };

            // Botón "Este mes"
            const btnEsteMes = document.createElement('button');
            btnEsteMes.type = 'button';
            btnEsteMes.className = 'btn btn-sm btn-outline-secondary mt-2 ml-2';
            btnEsteMes.innerHTML = '<i class="fas fa-calendar"></i> Este mes';
            btnEsteMes.onclick = function() {
                const ahora = new Date();
                document.querySelector('input[name="fecha_desde"]').value = new Date(ahora.getFullYear(), ahora.getMonth(), 1).toISOString().split('T')[0];
                document.querySelector('input[name="fecha_hasta"]').value = ahora.toISOString().split('T')[0];
            };

            // Agregar botones después del campo "Fecha Hasta"
            const fechaHastaGroup = document.querySelector('input[name="fecha_hasta"]').closest('.form-group');
            fechaHastaGroup.appendChild(btnUltimos7);
            fechaHastaGroup.appendChild(btnEsteMes);
        });
    </script>
@stop

