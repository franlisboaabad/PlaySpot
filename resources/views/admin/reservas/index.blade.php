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
                    <a href="{{ route('reservas.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva Reserva
                    </a>
                    <a href="{{ route('reservas.calendario') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-calendar"></i> Ver Calendario
                    </a>
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
                <table id="reservasTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Cancha</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->id }}</td>
                                <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
                                <td>{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</td>
                                <td>{{ $reserva->cancha->nombre }}</td>
                                <td>{{ $reserva->cliente->nombre }}</td>
                                <td>{{ $reserva->cliente->telefono }}</td>
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
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('reservas.ticket', $reserva) }}" download>
                                                <i class="fas fa-file-pdf text-danger"></i> Descargar Ticket PDF
                                            </a>
                                            <a class="dropdown-item" href="{{ route('reservas.show', $reserva) }}">
                                                <i class="fas fa-eye text-info"></i> Ver Detalles
                                            </a>
                                            <a class="dropdown-item" href="{{ route('reservas.edit', $reserva) }}">
                                                <i class="fas fa-edit text-warning"></i> Editar
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger btn-delete" data-title="¿Está seguro de eliminar esta reserva?" data-text="Esta acción no se puede deshacer.">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            $('#reservasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                },
                "order": [[0, "desc"]], // Ordenar por ID descendente
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "responsive": true
            });

            // Interceptar formularios de eliminación con SweetAlert
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
