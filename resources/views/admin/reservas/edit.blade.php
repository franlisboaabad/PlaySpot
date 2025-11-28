@extends('adminlte::page')

@section('title', 'Editar Reserva')

@section('content_header')
    <h1>Editar Reserva</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reservas.update', $reserva) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="cliente_id">Cliente *</label>
                            <select name="cliente_id" id="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror" required>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ $reserva->cliente_id == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} - {{ $cliente->telefono }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="cancha_id">Cancha *</label>
                            <select name="cancha_id" id="cancha_id" class="form-control @error('cancha_id') is-invalid @enderror" required>
                                @foreach($canchas as $cancha)
                                    <option value="{{ $cancha->id }}" {{ $reserva->cancha_id == $cancha->id ? 'selected' : '' }}>
                                        {{ $cancha->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cancha_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="fecha">Fecha *</label>
                            <input type="date" name="fecha" id="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', $reserva->fecha->format('Y-m-d')) }}" required>
                            @error('fecha')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="hora_inicio">Hora Inicio *</label>
                            <input type="time" name="hora_inicio" id="hora_inicio"
                                   class="form-control @error('hora_inicio') is-invalid @enderror"
                                   value="{{ old('hora_inicio', $reserva->hora_inicio) }}" required>
                            @error('hora_inicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="hora_fin">Hora Fin *</label>
                            <input type="time" name="hora_fin" id="hora_fin"
                                   class="form-control @error('hora_fin') is-invalid @enderror"
                                   value="{{ old('hora_fin', $reserva->hora_fin) }}" required>
                            @error('hora_fin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="estado">Estado *</label>
                            <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="pendiente" {{ $reserva->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ $reserva->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelada" {{ $reserva->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                <option value="completada" {{ $reserva->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                @if($errors->has('horario'))
                    <div class="alert alert-danger">
                        {{ $errors->first('horario') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control">{{ old('observaciones', $reserva->observaciones) }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md-inline">
                        <i class="fas fa-save"></i> Actualizar Reserva
                    </button>
                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary btn-md-inline">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Validación en tiempo real de horas
        document.addEventListener('DOMContentLoaded', function() {
            const horaInicio = document.getElementById('hora_inicio');
            const horaFin = document.getElementById('hora_fin');

            function validarHoras() {
                const inicio = horaInicio.value;
                const fin = horaFin.value;

                if (!inicio || !fin) {
                    return;
                }

                const inicioTime = new Date('2000-01-01T' + inicio + ':00');
                let finTime = new Date('2000-01-01T' + fin + ':00');

                // Si la hora fin es menor que inicio, asumimos que cruza medianoche
                if (finTime <= inicioTime) {
                    finTime = new Date('2000-01-02T' + fin + ':00');
                }

                const diferenciaMs = finTime - inicioTime;
                const diferenciaHoras = diferenciaMs / (1000 * 60 * 60);

                // Remover clases de error previas
                horaFin.classList.remove('is-invalid', 'is-valid');

                if (diferenciaHoras < 1) {
                    horaFin.classList.add('is-invalid');
                    horaFin.setCustomValidity('La hora de fin debe ser al menos 1 hora después de la hora de inicio.');
                } else if (diferenciaHoras > 24) {
                    horaFin.classList.add('is-invalid');
                    horaFin.setCustomValidity('La reserva no puede exceder 24 horas de duración.');
                } else {
                    horaFin.classList.add('is-valid');
                    horaFin.setCustomValidity('');
                }
            }

            horaInicio.addEventListener('change', validarHoras);
            horaFin.addEventListener('change', validarHoras);
            horaFin.addEventListener('input', validarHoras);

            // Validar al cargar la página si ya hay valores
            if (horaInicio.value && horaFin.value) {
                validarHoras();
            }
        });
    </script>
@stop

