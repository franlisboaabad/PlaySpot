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
                    <div class="float-md-right">
                        <a href="{{ route('reservas.exportar.pdf', request()->query()) }}" class="btn btn-danger btn-sm btn-md-inline" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('reservas.exportar.excel', request()->query()) }}" class="btn btn-success btn-sm btn-md-inline">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <form method="GET" action="{{ route('reservas.index') }}" class="form-inline">
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <label class="mr-2">Cancha:</label>
                            <select name="cancha_id" class="form-control form-control-sm">
                                <option value="">Todas</option>
                                @foreach($canchas as $cancha)
                                    <option value="{{ $cancha->id }}" {{ request('cancha_id') == $cancha->id ? 'selected' : '' }}>
                                        {{ $cancha->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <label class="mr-2">Desde:</label>
                            <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <label class="mr-2">Hasta:</label>
                            <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <label class="mr-2">Estado:</label>
                            <select name="estado" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <label class="mr-2">Cliente:</label>
                            <input type="text" name="cliente" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('cliente') }}">
                        </div>
                        <div class="form-group mb-2 mb-md-0">
                            <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
                            <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
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
                                        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-delete" data-title="¿Está seguro de eliminar esta reserva?" data-text="Esta acción no se puede deshacer.">
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
                                        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="¿Está seguro de eliminar esta reserva?" data-text="Esta acción no se puede deshacer.">
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

@section('js')
    <script>
        // Interceptar formularios de eliminación con SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.form-delete').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const button = form.querySelector('.btn-delete');
                    const title = button.getAttribute('data-title') || '¿Está seguro?';
                    const text = button.getAttribute('data-text') || 'Esta acción no se puede deshacer.';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@stop

