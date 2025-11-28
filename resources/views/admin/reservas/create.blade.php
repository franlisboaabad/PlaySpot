@extends('adminlte::page')

@section('title', 'Nueva Reserva')

@section('content_header')
    <h1>Nueva Reserva</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reservas.store') }}" method="POST" id="formReserva">
                @csrf

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="cliente_id">Cliente *</label>
                            <select name="cliente_id" id="cliente_id" class="form-control select2 @error('cliente_id') is-invalid @enderror" required>
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} - {{ $cliente->telefono }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                <a href="#" data-toggle="modal" data-target="#modalCrearCliente">Crear nuevo cliente</a>
                            </small>
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
                                <option value="">Seleccione una cancha</option>
                                @foreach($canchas as $cancha)
                                    <option value="{{ $cancha->id }}" {{ $canchaPreseleccionada == $cancha->id ? 'selected' : '' }}>
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
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="fecha">Fecha *</label>
                            <input type="date" name="fecha" id="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ $fechaPreseleccionada ?? old('fecha', date('Y-m-d')) }}"
                                   required>
                            @error('fecha')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="hora_inicio">Hora Inicio *</label>
                            <input type="time" name="hora_inicio" id="hora_inicio"
                                   class="form-control @error('hora_inicio') is-invalid @enderror"
                                   value="{{ old('hora_inicio', $horaInicioPreseleccionada ?? '') }}" required>
                            @error('hora_inicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="hora_fin">Hora Fin *</label>
                            <input type="time" name="hora_fin" id="hora_fin"
                                   class="form-control @error('hora_fin') is-invalid @enderror"
                                   value="{{ old('hora_fin', $horaFinPreseleccionada ?? '') }}" required>
                            @error('hora_fin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Área de disponibilidad -->
                <div id="disponibilidadArea" style="display: none;">
                    <div class="alert" id="mensajeDisponibilidad" role="alert">
                        <i class="fas fa-spinner fa-spin"></i> Verificando disponibilidad...
                    </div>

                    <div id="reservasExistentes" style="display: none;">
                        <h5>Reservas existentes para esta fecha:</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Horario</th>
                                        <th>Cliente</th>
                                        <th class="d-none d-md-table-cell">Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaReservas">
                                </tbody>
                            </table>
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
                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control">{{ old('observaciones') }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md-inline" id="btnGuardar">
                        <i class="fas fa-save"></i> Guardar Reserva
                    </button>
                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary btn-md-inline">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para crear cliente -->
    <div class="modal fade" id="modalCrearCliente" tabindex="-1" role="dialog" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearClienteLabel">Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCrearCliente">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_nombre">Nombre *</label>
                                    <input type="text" name="nombre" id="cliente_nombre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_telefono">Teléfono *</label>
                                    <input type="text" name="telefono" id="cliente_telefono" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_email">Email</label>
                                    <input type="email" name="email" id="cliente_email" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_dni">DNI</label>
                                    <input type="text" name="dni" id="cliente_dni" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cliente_direccion">Dirección</label>
                            <textarea name="direccion" id="cliente_direccion" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cliente_observaciones">Observaciones</label>
                            <textarea name="observaciones" id="cliente_observaciones" rows="2" class="form-control"></textarea>
                        </div>
                        <div id="mensajeCliente" class="alert" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Inicializar Select2 para el campo de cliente
        $(document).ready(function() {
            $('#cliente_id').select2({
                placeholder: 'Seleccione un cliente',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron clientes";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                },
                width: '100%'
            });
        });

        let timeoutVerificacion;

        // Función para verificar disponibilidad
        function verificarDisponibilidad() {
            const canchaId = document.getElementById('cancha_id').value;
            const fecha = document.getElementById('fecha').value;
            const horaInicio = document.getElementById('hora_inicio').value;
            const horaFin = document.getElementById('hora_fin').value;

            const disponibilidadArea = document.getElementById('disponibilidadArea');
            const mensajeDisponibilidad = document.getElementById('mensajeDisponibilidad');
            const btnGuardar = document.getElementById('btnGuardar');

            // Validar que todos los campos estén llenos
            if (!canchaId || !fecha || !horaInicio || !horaFin) {
                disponibilidadArea.style.display = 'none';
                return;
            }

            // Validar que hora_fin > hora_inicio
            if (horaFin <= horaInicio) {
                disponibilidadArea.style.display = 'block';
                mensajeDisponibilidad.className = 'alert alert-warning';
                mensajeDisponibilidad.innerHTML = '<i class="fas fa-exclamation-triangle"></i> La hora de fin debe ser mayor que la hora de inicio';
                btnGuardar.disabled = true;
                return;
            }

            disponibilidadArea.style.display = 'block';
            mensajeDisponibilidad.className = 'alert alert-info';
            mensajeDisponibilidad.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando disponibilidad...';
            btnGuardar.disabled = true;

            // Cargar reservas existentes
            fetch(`{{ route('api.reservas.por-fecha') }}?cancha_id=${canchaId}&fecha=${fecha}`)
                .then(response => response.json())
                .then(reservas => {
                    mostrarReservasExistentes(reservas);

                    // Verificar disponibilidad del horario seleccionado
                    return fetch('{{ route("api.reservas.verificar") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cancha_id: canchaId,
                            fecha: fecha,
                            hora_inicio: horaInicio,
                            hora_fin: horaFin
                        })
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.disponible) {
                        mensajeDisponibilidad.className = 'alert alert-success';
                        mensajeDisponibilidad.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Horario disponible</strong> - Puede proceder a guardar la reserva';
                        btnGuardar.disabled = false;
                    } else {
                        mensajeDisponibilidad.className = 'alert alert-danger';
                        mensajeDisponibilidad.innerHTML = '<i class="fas fa-times-circle"></i> <strong>Horario ocupado</strong> - El horario seleccionado ya está reservado. Por favor, seleccione otro horario.';
                        btnGuardar.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mensajeDisponibilidad.className = 'alert alert-warning';
                    mensajeDisponibilidad.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error al verificar disponibilidad';
                    btnGuardar.disabled = false;
                });
        }

        // Función para mostrar reservas existentes
        function mostrarReservasExistentes(reservas) {
            const tablaReservas = document.getElementById('tablaReservas');
            const reservasExistentes = document.getElementById('reservasExistentes');

            if (reservas.length === 0) {
                reservasExistentes.style.display = 'none';
                return;
            }

            reservasExistentes.style.display = 'block';
            tablaReservas.innerHTML = '';

            reservas.forEach(reserva => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${reserva.hora_inicio} - ${reserva.hora_fin}</strong></td>
                    <td>${reserva.cliente}</td>
                    <td>${reserva.telefono}</td>
                `;
                tablaReservas.appendChild(row);
            });
        }

        // Event listeners
        document.getElementById('cancha_id').addEventListener('change', function() {
            clearTimeout(timeoutVerificacion);
            timeoutVerificacion = setTimeout(verificarDisponibilidad, 500);
        });

        document.getElementById('fecha').addEventListener('change', function() {
            clearTimeout(timeoutVerificacion);
            timeoutVerificacion = setTimeout(verificarDisponibilidad, 500);
        });

        document.getElementById('hora_inicio').addEventListener('change', function() {
            clearTimeout(timeoutVerificacion);
            timeoutVerificacion = setTimeout(verificarDisponibilidad, 500);
        });

        document.getElementById('hora_fin').addEventListener('change', function() {
            const horaInicio = document.getElementById('hora_inicio').value;
            const horaFin = this.value;

            if (horaInicio && horaFin && horaFin <= horaInicio) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error en horario',
                    text: 'La hora de fin debe ser mayor que la hora de inicio',
                    confirmButtonText: 'Entendido'
                });
                this.value = '';
                return;
            }

            clearTimeout(timeoutVerificacion);
            timeoutVerificacion = setTimeout(verificarDisponibilidad, 500);
        });

        // Validar antes de enviar
        document.getElementById('formReserva').addEventListener('submit', function(e) {
            const btnGuardar = document.getElementById('btnGuardar');
            if (btnGuardar.disabled) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Espere por favor',
                    text: 'Por favor, espere a que se verifique la disponibilidad o seleccione un horario disponible.',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }
        });

        // Manejar creación de cliente en modal
        document.getElementById('formCrearCliente').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const mensajeCliente = document.getElementById('mensajeCliente');
            const btnSubmit = this.querySelector('button[type="submit"]');

            mensajeCliente.style.display = 'none';
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

            fetch('{{ route("clientes.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Error al crear el cliente');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Agregar el nuevo cliente al select con Select2
                    const nuevoOption = new Option(
                        data.cliente.nombre + ' - ' + data.cliente.telefono,
                        data.cliente.id,
                        true,
                        true
                    );
                    $('#cliente_id').append(nuevoOption).trigger('change');

                    // Mostrar mensaje de éxito con SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente creado!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Limpiar el formulario
                    document.getElementById('formCrearCliente').reset();

                    // Cerrar el modal después de mostrar el mensaje
                    setTimeout(function() {
                        $('#modalCrearCliente').modal('hide');
                    }, 500);
                } else {
                    throw new Error(data.message || 'Error al crear el cliente');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al crear el cliente. Por favor, intente nuevamente.',
                    confirmButtonText: 'Entendido'
                });
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-save"></i> Guardar Cliente';
            });
        });

        // Limpiar mensaje al cerrar el modal
        $('#modalCrearCliente').on('hidden.bs.modal', function () {
            document.getElementById('formCrearCliente').reset();
            document.getElementById('mensajeCliente').style.display = 'none';
        });
    </script>
@stop
