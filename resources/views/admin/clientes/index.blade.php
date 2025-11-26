@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <h1>Clientes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm btn-md-inline">
                        <i class="fas fa-plus"></i> Nuevo Cliente
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <form method="GET" action="{{ route('clientes.index') }}" class="form-inline float-md-right">
                        <div class="form-group mb-2 mb-md-0 mr-md-2">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group mb-2 mb-md-0">
                            <button type="submit" class="btn btn-secondary btn-sm">Buscar</button>
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
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th class="d-none d-md-table-cell">Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->id }}</td>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->telefono }}</td>
                                <td class="d-none d-md-table-cell">{{ $cliente->email ?? '-' }}</td>
                                <td>
                                    <div class="btn-group-vertical btn-group-sm d-md-none" role="group">
                                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-info mb-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning mb-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="btn-group d-none d-md-inline-flex" role="group">
                                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay clientes registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $clientes->links() }}
            </div>
        </div>
    </div>
@stop

