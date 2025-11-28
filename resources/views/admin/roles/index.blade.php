@extends('adminlte::page')

@section('title', 'Lista de roles')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <div class="card">
        <div class="card-body">
            <a href="{{ route('roles.create') }}">Nuevo Rol</a> <hr>
            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Role</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="form-delete">

                                    <a href="{{ route('roles.edit', $role ) }}" class="btn btn-info btn-xs">Editar</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs btn-delete" data-title="¿Está seguro de eliminar este rol?" data-text="Esta acción no se puede deshacer.">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
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
