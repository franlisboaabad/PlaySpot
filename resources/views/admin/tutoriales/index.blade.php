@extends('adminlte::page')

@section('title', 'Tutoriales y Uso del Sistema')

@section('content_header')
    <h1><i class="fas fa-book"></i> Tutoriales y Uso del Sistema</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Navegación rápida -->
            <div class="card card-primary card-outline collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Índice de Contenidos
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="#inicio" class="nav-link">
                                <i class="fas fa-play-circle"></i> Inicio Rápido
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#reservas" class="nav-link">
                                <i class="fas fa-calendar-alt"></i> Gestión de Reservas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#calendario" class="nav-link">
                                <i class="fas fa-calendar"></i> Calendario de Reservas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#clientes" class="nav-link">
                                <i class="fas fa-users"></i> Gestión de Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#canchas" class="nav-link">
                                <i class="fas fa-futbol"></i> Gestión de Canchas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#reportes" class="nav-link">
                                <i class="fas fa-chart-bar"></i> Reportes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#preguntas" class="nav-link">
                                <i class="fas fa-question-circle"></i> Preguntas Frecuentes
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Inicio Rápido -->
            <div class="card card-outline card-info" id="inicio">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-play-circle"></i> Inicio Rápido
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Bienvenido al Sistema de Reservas de Canchas</h4>
                    <p>Este sistema te permite gestionar las reservas de canchas deportivas de manera eficiente y en tiempo real.</p>

                    <h5>Pasos para comenzar:</h5>
                    <ol>
                        <li><strong>Configurar Canchas:</strong> Ve a <a href="{{ route('canchas.index') }}">Canchas</a> y agrega las canchas disponibles.</li>
                        <li><strong>Registrar Clientes:</strong> Ve a <a href="{{ route('clientes.index') }}">Clientes</a> y agrega los clientes que alquilarán las canchas.</li>
                        <li><strong>Crear Reservas:</strong> Ve a <a href="{{ route('reservas.create') }}">Nueva Reserva</a> o usa el <a href="{{ route('reservas.calendario') }}">Calendario</a> para crear reservas.</li>
                        <li><strong>Ver Reportes:</strong> Accede a <a href="{{ route('reportes.ocupacion') }}">Reportes</a> para analizar la ocupación de canchas.</li>
                    </ol>

                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Tip Importante</h5>
                        El sistema valida automáticamente que no haya conflictos de horarios. Si intentas reservar un horario ya ocupado, recibirás una advertencia.
                    </div>
                </div>
            </div>

            <!-- Gestión de Reservas -->
            <div class="card card-outline card-success" id="reservas">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt"></i> Gestión de Reservas
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Crear una Nueva Reserva</h4>
                    <p>Para crear una reserva, tienes dos opciones:</p>

                    <h5>Opción 1: Desde el Formulario</h5>
                    <ol>
                        <li>Haz clic en <strong>"Nueva Reserva"</strong> en el menú o en el botón de la lista de reservas.</li>
                        <li>Selecciona el <strong>Cliente</strong> del menú desplegable. Si no existe, puedes crearlo haciendo clic en "Crear nuevo cliente".</li>
                        <li>Selecciona la <strong>Cancha</strong> que deseas reservar.</li>
                        <li>Elige la <strong>Fecha</strong> de la reserva.</li>
                        <li>Ingresa la <strong>Hora de Inicio</strong> y <strong>Hora de Fin</strong>.</li>
                        <li>El sistema verificará automáticamente la disponibilidad y te mostrará si el horario está libre.</li>
                        <li>Si el horario está disponible, haz clic en <strong>"Guardar Reserva"</strong>.</li>
                    </ol>

                    <h5>Opción 2: Desde el Calendario</h5>
                    <ol>
                        <li>Ve al <strong>Calendario de Reservas</strong>.</li>
                        <li>Haz clic directamente en el horario y día que deseas reservar.</li>
                        <li>El formulario se abrirá con la fecha y hora ya preseleccionadas.</li>
                        <li>Completa los demás campos y guarda.</li>
                    </ol>

                    <h4>Editar una Reserva</h4>
                    <ol>
                        <li>Ve a la lista de reservas.</li>
                        <li>Haz clic en el botón <strong>"Editar"</strong> (ícono de lápiz) de la reserva que deseas modificar.</li>
                        <li>Modifica los campos necesarios.</li>
                        <li>El sistema validará que el nuevo horario no entre en conflicto con otras reservas.</li>
                        <li>Haz clic en <strong>"Actualizar Reserva"</strong>.</li>
                    </ol>

                    <h4>Cancelar una Reserva</h4>
                    <ol>
                        <li>Edita la reserva que deseas cancelar.</li>
                        <li>Cambia el <strong>Estado</strong> a <strong>"Cancelada"</strong>.</li>
                        <li>Guarda los cambios.</li>
                    </ol>

                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Importante</h5>
                        <ul>
                            <li>Las reservas canceladas no aparecen en el calendario.</li>
                            <li>No puedes crear dos reservas en la misma cancha con horarios que se solapen.</li>
                            <li>Las reservas pueden cruzar la medianoche (ej: 23:00 a 01:00).</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Calendario -->
            <div class="card card-outline card-primary" id="calendario">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar"></i> Calendario de Reservas
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Vista de Calendario</h4>
                    <p>El calendario te permite visualizar todas las reservas de manera gráfica y crear nuevas reservas rápidamente.</p>

                    <h5>Funcionalidades del Calendario:</h5>
                    <ul>
                        <li><strong>Vista Semanal:</strong> Muestra las reservas de la semana actual con sus horarios.</li>
                        <li><strong>Vista Mensual:</strong> Vista general del mes con todas las reservas.</li>
                        <li><strong>Vista Diaria:</strong> Detalle de un día específico.</li>
                        <li><strong>Filtro por Cancha:</strong> Puedes filtrar para ver solo una cancha específica o todas.</li>
                        <li><strong>Crear Reserva Rápida:</strong> Haz clic en cualquier horario libre para crear una reserva.</li>
                    </ul>

                    <h5>Cómo usar el Calendario:</h5>
                    <ol>
                        <li>Usa los botones de navegación (<strong>◀</strong> <strong>▶</strong>) para moverte entre semanas/meses.</li>
                        <li>Haz clic en <strong>"Hoy"</strong> para volver a la fecha actual.</li>
                        <li>Usa los botones de filtro de canchas para ver solo una cancha específica.</li>
                        <li>Haz clic en cualquier evento (reserva) para ver sus detalles.</li>
                        <li>Haz clic en un horario libre para crear una nueva reserva.</li>
                    </ol>

                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Tip</h5>
                        En dispositivos móviles, el calendario se muestra en vista diaria por defecto para mejor visualización.
                    </div>
                </div>
            </div>

            <!-- Gestión de Clientes -->
            <div class="card card-outline card-warning" id="clientes">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Gestión de Clientes
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Agregar un Nuevo Cliente</h4>
                    <ol>
                        <li>Ve a <strong>Clientes</strong> en el menú.</li>
                        <li>Haz clic en <strong>"Nuevo Cliente"</strong>.</li>
                        <li>Completa los campos:
                            <ul>
                                <li><strong>Nombre:</strong> Nombre completo del cliente (requerido)</li>
                                <li><strong>Teléfono:</strong> Número de contacto (requerido)</li>
                                <li><strong>Email:</strong> Correo electrónico (opcional)</li>
                                <li><strong>DNI:</strong> Documento de identidad (opcional)</li>
                                <li><strong>Dirección:</strong> Dirección del cliente (opcional)</li>
                                <li><strong>Observaciones:</strong> Notas adicionales (opcional)</li>
                            </ul>
                        </li>
                        <li>Haz clic en <strong>"Guardar"</strong>.</li>
                    </ol>

                    <h4>Buscar Clientes</h4>
                    <p>En la lista de clientes, puedes usar el campo de búsqueda para encontrar clientes por nombre o teléfono.</p>

                    <h4>Crear Cliente desde el Formulario de Reserva</h4>
                    <p>Al crear una reserva, si el cliente no existe, puedes crearlo directamente desde el modal sin salir del formulario:</p>
                    <ol>
                        <li>En el campo "Cliente", haz clic en <strong>"Crear nuevo cliente"</strong>.</li>
                        <li>Completa el formulario en el modal.</li>
                        <li>El cliente se guardará y se seleccionará automáticamente en la reserva.</li>
                    </ol>

                    <h4>Ver Historial de Reservas de un Cliente</h4>
                    <ol>
                        <li>Ve a la lista de clientes.</li>
                        <li>Haz clic en el botón <strong>"Ver"</strong> (ícono de ojo) del cliente.</li>
                        <li>Verás todos los detalles del cliente y su historial de reservas.</li>
                    </ol>
                </div>
            </div>

            <!-- Gestión de Canchas -->
            <div class="card card-outline card-danger" id="canchas">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-futbol"></i> Gestión de Canchas
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Agregar una Nueva Cancha</h4>
                    <ol>
                        <li>Ve a <strong>Canchas</strong> en el menú.</li>
                        <li>Haz clic en <strong>"Nueva Cancha"</strong>.</li>
                        <li>Completa los campos:
                            <ul>
                                <li><strong>Nombre:</strong> Nombre de la cancha (ej: "Cancha 1", "Cancha Fútbol")</li>
                                <li><strong>Descripción:</strong> Descripción opcional de la cancha</li>
                                <li><strong>Estado:</strong> Activa o Inactiva (solo las canchas activas aparecen para reservar)</li>
                            </ul>
                        </li>
                        <li>Haz clic en <strong>"Guardar"</strong>.</li>
                    </ol>

                    <h4>Activar/Desactivar Canchas</h4>
                    <p>Puedes desactivar una cancha temporalmente editándola y cambiando su estado a "Inactiva". Las canchas inactivas no aparecerán en las opciones de reserva, pero sus reservas existentes se mantendrán.</p>

                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Importante</h5>
                        Si desactivas una cancha, no podrás crear nuevas reservas para ella, pero las reservas existentes seguirán visibles.
                    </div>
                </div>
            </div>

            <!-- Reportes -->
            <div class="card card-outline card-info" id="reportes">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Reporte de Reservas</h4>
                    <p>El listado de reservas incluye funciones avanzadas de filtrado y exportación:</p>

                    <h5>Filtros Disponibles:</h5>
                    <ul>
                        <li><strong>Cancha:</strong> Filtrar por cancha específica o ver todas</li>
                        <li><strong>Fecha Desde/Hasta:</strong> Seleccionar un rango de fechas</li>
                        <li><strong>Estado:</strong> Filtrar por estado (Pendiente, Confirmada, Cancelada, Completada)</li>
                        <li><strong>Cliente:</strong> Buscar reservas de un cliente específico</li>
                    </ul>

                    <h5>Exportar Reportes:</h5>
                    <ul>
                        <li><strong>Exportar a PDF:</strong> Genera un documento PDF con las reservas filtradas, listo para imprimir o compartir.</li>
                        <li><strong>Exportar a Excel:</strong> Genera un archivo CSV compatible con Excel para análisis de datos.</li>
                    </ul>

                    <h4>Reporte de Ocupación de Canchas</h4>
                    <p>Este reporte te permite analizar el rendimiento y ocupación de tus canchas:</p>

                    <h5>Información que muestra:</h5>
                    <ul>
                        <li><strong>Resumen por Cancha:</strong> Horas reservadas, porcentaje de ocupación, total de reservas</li>
                        <li><strong>Horarios Más Solicitados:</strong> Qué horas del día tienen más demanda</li>
                        <li><strong>Reservas por Día de la Semana:</strong> Qué días son más populares</li>
                        <li><strong>Evolución Temporal:</strong> Cómo ha variado la demanda en el tiempo</li>
                    </ul>

                    <h5>Cómo usar el Reporte de Ocupación:</h5>
                    <ol>
                        <li>Ve a <strong>Reportes → Ocupación de Canchas</strong>.</li>
                        <li>Selecciona el rango de fechas que deseas analizar.</li>
                        <li>Usa los botones rápidos "Últimos 7 días" o "Este mes" para análisis comunes.</li>
                        <li>Revisa los gráficos y tablas para identificar patrones y oportunidades.</li>
                    </ol>

                    <div class="alert alert-success">
                        <h5><i class="icon fas fa-check"></i> Tip</h5>
                        Usa el reporte de ocupación para identificar horarios con baja demanda y ofrecer promociones, o para planificar mejor la disponibilidad de canchas.
                    </div>
                </div>
            </div>

            <!-- Preguntas Frecuentes -->
            <div class="card card-outline card-secondary" id="preguntas">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle"></i> Preguntas Frecuentes
                    </h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="card">
                            <div class="card-header" id="faq1">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse1">
                                        ¿Puedo reservar una cancha para más de un día?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse1" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body">
                                    No, cada reserva es para un solo día. Si necesitas reservar para varios días, debes crear una reserva por cada día.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faq2">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse2">
                                        ¿Puedo hacer reservas que crucen la medianoche?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse2" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Sí, puedes crear reservas que crucen la medianoche. Por ejemplo, de 23:00 a 01:00. El sistema lo maneja correctamente.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faq3">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse3">
                                        ¿Qué pasa si intento reservar un horario ya ocupado?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse3" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body">
                                    El sistema te mostrará un mensaje de error indicando que el horario ya está ocupado. También verás una tabla con las reservas existentes para ese día y cancha, para que puedas elegir otro horario disponible.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faq4">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse4">
                                        ¿Puedo cancelar una reserva?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse4" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Sí, puedes editar la reserva y cambiar su estado a "Cancelada". Las reservas canceladas no aparecen en el calendario pero se mantienen en el historial.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faq5">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse5">
                                        ¿Cómo exporto los reportes?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse5" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body">
                                    En la lista de reservas, después de aplicar los filtros que necesites, haz clic en el botón "PDF" para exportar a PDF o "Excel" para exportar a CSV. El archivo se descargará con los datos filtrados.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faq6">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse6">
                                        ¿Puedo ver el historial de reservas de un cliente?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse6" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Sí, ve a la lista de clientes, haz clic en "Ver" (ícono de ojo) del cliente y verás todos sus detalles junto con su historial completo de reservas.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faq7">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse7">
                                        ¿El sistema funciona en dispositivos móviles?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse7" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Sí, el sistema es completamente responsive y funciona perfectamente en tablets y smartphones. El calendario se adapta automáticamente a pantallas pequeñas.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacto y Soporte -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-life-ring"></i> ¿Necesitas Ayuda?
                    </h3>
                </div>
                <div class="card-body">
                    <p>Si tienes alguna duda o problema con el sistema, contacta al administrador del sistema.</p>
                    <p>Recuerda que puedes usar el <strong>Dashboard</strong> para ver un resumen rápido de tus reservas y estadísticas del día.</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            margin-bottom: 20px;
        }
        .nav-pills .nav-link {
            border-radius: 0.25rem;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: block;
            padding: 0.5rem 1rem;
        }
        .nav-pills .nav-link:hover,
        .nav-pills .nav-link:focus,
        .nav-pills .nav-link:active {
            background-color: #007bff !important;
            color: white !important;
            transform: translateX(5px);
            text-decoration: none;
        }
        .nav-pills .nav-link i {
            margin-right: 8px;
        }
        .accordion .card-header button {
            color: #333;
            text-decoration: none;
            width: 100%;
            text-align: left;
            transition: all 0.2s ease;
        }
        .accordion .card-header button:hover,
        .accordion .card-header button:focus {
            color: #007bff !important;
            text-decoration: none;
        }
        .accordion .card-header button:not(.collapsed) {
            color: #007bff;
        }
    </style>
@stop

@section('js')
    <script>
        // Smooth scroll para los enlaces del índice
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@stop

