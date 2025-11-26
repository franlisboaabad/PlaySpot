@extends('adminlte::page')

@section('title', 'Detalle de Cliente')

@section('content_header')
    <h1>Detalle de Cliente</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <h5>Información del Cliente</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $cliente->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $cliente->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>{{ $cliente->telefono }}</td>
                        </tr>
                        @if($cliente->email)
                        <tr>
                            <th>Email:</th>
                            <td>{{ $cliente->email }}</td>
                        </tr>
                        @endif
                        @if($cliente->dni)
                        <tr>
                            <th>DNI:</th>
                            <td>{{ $cliente->dni }}</td>
                        </tr>
                        @endif
                        @if($cliente->direccion)
                        <tr>
                            <th>Dirección:</th>
                            <td>{{ $cliente->direccion }}</td>
                        </tr>
                        @endif
                        @if($cliente->observaciones)
                        <tr>
                            <th>Observaciones:</th>
                            <td>{{ $cliente->observaciones }}</td>
                        </tr>
                        @endif
                    </table>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <h5>Reservas del Cliente</h5>
                    @if($cliente->reservas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Cancha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->reservas as $reserva)
                                    <tr>
                                        <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
                                        <td>{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</td>
                                        <td>{{ $reserva->cancha->nombre }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        <p class="text-muted">Este cliente no tiene reservas.</p>
                    @endif
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-md-inline">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-md-inline">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
@stop

