@extends('adminlte::page')

@section('title', 'Canchas')

@section('content_header')
    <h1>Canchas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('canchas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Cancha
            </a>
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
                            <th>Nombre</th>
                            <th class="d-none d-md-table-cell">Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($canchas as $cancha)
                            <tr>
                                <td>{{ $cancha->id }}</td>
                                <td>{{ $cancha->nombre }}</td>
                                <td class="d-none d-md-table-cell">{{ $cancha->descripcion ?? '-' }}</td>
                                <td>
                                    @if($cancha->activa)
                                        <span class="badge badge-success">Activa</span>
                                    @else
                                        <span class="badge badge-danger">Inactiva</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group-vertical btn-group-sm d-md-none" role="group">
                                        <a href="{{ route('canchas.edit', $cancha) }}" class="btn btn-warning mb-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('canchas.destroy', $cancha) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta cancha?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="btn-group d-none d-md-inline-flex" role="group">
                                        <a href="{{ route('canchas.edit', $cancha) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('canchas.destroy', $cancha) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta cancha?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay canchas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

