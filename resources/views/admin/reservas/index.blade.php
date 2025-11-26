@extends('adminlte::page')

@section('title', 'Reservas')

@section('content_header')
    <h1>Reservas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <a href="{{ route('reservas.create') }}" class="btn btn-primary btn-sm btn-md-inline">
                        <i class="fas fa-plus"></i> Nueva Reserva
                    </a>
                    <a href="{{ route('reservas.calendario') }}" class="btn btn-info btn-sm btn-md-inline">
                        <i class="fas fa-calendar"></i> Ver Calendario
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <form method="GET" action="{{ route('reservas.index') }}" class="form-inline float-md-right">
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <select name="cancha_id" class="form-control form-control-sm">
                                <option value="">Todas las canchas</option>
                                @foreach($canchas as $cancha)
                                    <option value="{{ $cancha->id }}" {{ request('cancha_id') == $cancha->id ? 'selected' : '' }}>
                                        {{ $cancha->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <input type="date" name="fecha" class="form-control form-control-sm" value="{{ request('fecha') }}">
                        </div>
                        <div class="form-group mb-2 mb-md-0">
                            <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Cancha</th>
                            <th class="d-none d-md-table-cell">Cliente</th>
                            <th class="d-none d-lg-table-cell">Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->id }}</td>
                                <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
                                <td>{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</td>
                                <td>{{ $reserva->cancha->nombre }}</td>
                                <td class="d-none d-md-table-cell">{{ $reserva->cliente->nombre }}</td>
                                <td class="d-none d-lg-table-cell">{{ $reserva->cliente->telefono }}</td>
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
                                <td>
                                    <div class="btn-group-vertical btn-group-sm d-md-none" role="group">
                                        <a href="{{ route('reservas.show', $reserva) }}" class="btn btn-info mb-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-warning mb-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta reserva?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="btn-group d-none d-md-inline-flex" role="group">
                                        <a href="{{ route('reservas.show', $reserva) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta reserva?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay reservas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $reservas->links() }}
            </div>
        </div>
    </div>
@stop

