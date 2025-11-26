@extends('adminlte::page')

@section('title', 'Editar Cancha')

@section('content_header')
    <h1>Editar Cancha</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('canchas.update', $cancha) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $cancha->nombre) }}" required>
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion', $cancha->descripcion) }}</textarea>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="activa" id="activa" class="form-check-input" value="1" {{ old('activa', $cancha->activa) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activa">
                            Cancha activa
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md-inline">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('canchas.index') }}" class="btn btn-secondary btn-md-inline">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

