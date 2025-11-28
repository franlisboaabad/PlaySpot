@extends('adminlte::page')

@section('title', 'Calendario de Reservas')

@section('content_header')
    <h1>Calendario de Reservas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <a href="{{ route('reservas.create') }}" class="btn btn-primary btn-sm btn-md-inline">
                        <i class="fas fa-plus"></i> Nueva Reserva
                    </a>
                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary btn-sm btn-md-inline">
                        <i class="fas fa-list"></i> Ver Lista
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <div class="btn-group btn-group-sm float-md-right" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="cambiarCancha(0)">
                            Todas
                        </button>
                        @foreach($canchas as $cancha)
                            <button type="button" class="btn btn-outline-primary" onclick="cambiarCancha({{ $cancha->id }})">
                                {{ $cancha->nombre }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
@stop

@section('css')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
@stop

@section('js')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        let canchaFiltro = 0;
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: window.innerWidth < 768 ? 'timeGridDay' : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('{{ route("api.reservas.calendario") }}?cancha=' + canchaFiltro)
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            failureCallback(error);
                        });
                },
                eventClick: function(info) {
                    Swal.fire({
                        title: 'Información de la Reserva',
                        html: '<div class="text-left">' +
                              '<p><strong>Cliente:</strong> ' + info.event.extendedProps.cliente + '</p>' +
                              '<p><strong>Cancha:</strong> ' + info.event.extendedProps.cancha + '</p>' +
                              '<p><strong>Teléfono:</strong> ' + info.event.extendedProps.telefono + '</p>' +
                              '<p><strong>Estado:</strong> ' + info.event.extendedProps.estado + '</p>' +
                              '</div>',
                        icon: 'info',
                        confirmButtonText: 'Cerrar'
                    });
                },
                dateClick: function(info) {
                    // Obtener la hora del click
                    let horaInicio = '';
                    let horaFin = '';

                    if (info.allDay === false && info.date) {
                        // Si es un click en un slot de tiempo específico
                        const fecha = new Date(info.date);
                        const horas = fecha.getHours().toString().padStart(2, '0');
                        const minutos = fecha.getMinutes().toString().padStart(2, '0');
                        horaInicio = horas + ':' + minutos;

                        // Calcular hora fin (1 hora después por defecto)
                        const horaFinDate = new Date(fecha);
                        horaFinDate.setHours(horaFinDate.getHours() + 1);
                        const horasFin = horaFinDate.getHours().toString().padStart(2, '0');
                        const minutosFin = horaFinDate.getMinutes().toString().padStart(2, '0');
                        horaFin = horasFin + ':' + minutosFin;
                    }

                    // Construir URL con parámetros
                    let url = '{{ route("reservas.create") }}?fecha=' + info.dateStr.split('T')[0];

                    if (canchaFiltro > 0) {
                        url += '&cancha_id=' + canchaFiltro;
                    }

                    if (horaInicio) {
                        url += '&hora_inicio=' + horaInicio;
                    }

                    if (horaFin) {
                        url += '&hora_fin=' + horaFin;
                    }

                    window.location.href = url;
                },
                slotMinTime: '00:00:00',
                slotMaxTime: '24:00:00',
                height: 'auto',
                allDaySlot: false,
                // Configuración responsiva
                windowResize: function() {
                    if (window.innerWidth < 768) {
                        calendar.changeView('timeGridDay');
                    } else {
                        if (calendar.view.type === 'timeGridDay') {
                            calendar.changeView('timeGridWeek');
                        }
                    }
                }
            });

            calendar.render();
        });

        function cambiarCancha(canchaId) {
            canchaFiltro = canchaId;
            calendar.refetchEvents();
        }
    </script>
@stop

