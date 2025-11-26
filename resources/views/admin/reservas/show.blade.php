@extends('adminlte::page')

@section('title', 'Detalle de Reserva')

@section('content_header')
    <h1>Detalle de Reserva</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <h5>Información de la Reserva</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $reserva->id }}</td>
                        </tr>
                        <tr>
                            <th>Fecha:</th>
                            <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Horario:</th>
                            <td>{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</td>
                        </tr>
                        <tr>
                            <th>Cancha:</th>
                            <td>{{ $reserva->cancha->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
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
                        @if($reserva->observaciones)
                        <tr>
                            <th>Observaciones:</th>
                            <td>{{ $reserva->observaciones }}</td>
                        </tr>
                        @endif
                    </table>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <h5>Información del Cliente</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $reserva->cliente->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>{{ $reserva->cliente->telefono }}</td>
                        </tr>
                        @if($reserva->cliente->email)
                        <tr>
                            <th>Email:</th>
                            <td>{{ $reserva->cliente->email }}</td>
                        </tr>
                        @endif
                        @if($reserva->cliente->dni)
                        <tr>
                            <th>DNI:</th>
                            <td>{{ $reserva->cliente->dni }}</td>
                        </tr>
                        @endif
                    </table>
                    </div>

                    <h5 class="mt-3">Información del Sistema</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <tr>
                            <th>Creada por:</th>
                            <td>{{ $reserva->usuario->name }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de creación:</th>
                            <td>{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-warning btn-md-inline">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('reservas.index') }}" class="btn btn-secondary btn-md-inline">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
@stop

