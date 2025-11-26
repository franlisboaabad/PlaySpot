# 📅 Calendario Dinámico para Reservas

## 🎯 Opciones de Calendarios para Laravel

### **Opción 1: FullCalendar (Recomendado) ⭐**

**FullCalendar** es la librería más popular y completa para calendarios interactivos.

#### Características:
- ✅ Vista mensual, semanal, diaria
- ✅ Arrastrar y soltar eventos
- ✅ Click en día/hora para crear reserva
- ✅ Colores por cancha
- ✅ Actualización en tiempo real
- ✅ Responsive (móvil y desktop)

#### Ejemplo Visual:
```
┌─────────────────────────────────────────────────────────┐
│  ← Enero 2024 →                                         │
├─────────────────────────────────────────────────────────┤
│  Lun    Mar    Mié    Jue    Vie    Sáb    Dom         │
├─────────────────────────────────────────────────────────┤
│         │      │      │      │      │      │            │
│         │      │      │      │      │      │            │
│   1     │  2   │  3   │  4   │  5   │  6   │     7      │
│         │      │      │      │      │      │            │
│         │      │      │      │      │      │            │
├─────────────────────────────────────────────────────────┤
│   8     │  9   │ 10   │ 11   │ 12   │ 13   │    14      │
│         │      │      │      │      │      │            │
│         │      │      │      │      │      │            │
├─────────────────────────────────────────────────────────┤
│  15     │ 16   │ 17   │ 18   │ 19   │ 20   │    21      │
│  [10-11]│      │      │      │      │      │            │
│  Juan   │      │      │      │      │      │            │
├─────────────────────────────────────────────────────────┤
│  22     │ 23   │ 24   │ 25   │ 26   │ 27   │    28      │
│         │      │      │      │      │      │            │
│         │      │      │      │      │      │            │
└─────────────────────────────────────────────────────────┘
```

#### Instalación:
```bash
# Instalar FullCalendar via npm
npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/interaction
```

#### Código de Ejemplo:
```html
<!-- resources/views/reservas/calendario.blade.php -->
<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '/api/reservas', // Endpoint que devuelve las reservas
        eventClick: function(info) {
            // Mostrar detalles de la reserva
            alert('Reserva: ' + info.event.title);
        },
        dateClick: function(info) {
            // Abrir modal para crear nueva reserva
            abrirModalCrearReserva(info.dateStr);
        },
        eventDrop: function(info) {
            // Si se arrastra un evento, actualizar horario
            actualizarReserva(info.event.id, info.event.start);
        }
    });
    calendar.render();
});
</script>
```

---

### **Opción 2: Laravel Livewire + Alpine.js**

**Livewire** permite crear componentes interactivos sin escribir JavaScript.

#### Ventajas:
- ✅ Todo en PHP (más fácil para Laravel)
- ✅ Actualización automática
- ✅ Integración perfecta con Laravel

#### Ejemplo:
```php
// app/Http/Livewire/CalendarioReservas.php
class CalendarioReservas extends Component
{
    public $fechaSeleccionada;
    public $canchaSeleccionada;
    
    public function render()
    {
        $reservas = Reserva::where('fecha', $this->fechaSeleccionada)
            ->where('cancha_id', $this->canchaSeleccionada)
            ->get();
            
        return view('livewire.calendario-reservas', [
            'reservas' => $reservas
        ]);
    }
}
```

---

### **Opción 3: Vue.js + V-Calendar**

Si prefieres Vue.js, **V-Calendar** es excelente.

#### Características:
- ✅ Componente Vue nativo
- ✅ Muy personalizable
- ✅ Buen rendimiento

---

### **Opción 4: Calendario Personalizado con Bootstrap**

Calendario simple hecho a medida con Bootstrap (ya lo tienes instalado).

#### Ventajas:
- ✅ Sin dependencias adicionales
- ✅ Control total del diseño
- ✅ Más ligero

---

## 🎨 Recomendación: FullCalendar

**Te recomiendo FullCalendar** porque:

1. ✅ **Muy completo**: Tiene todo lo que necesitas
2. ✅ **Bien documentado**: Muchos ejemplos
3. ✅ **Comunidad grande**: Fácil encontrar ayuda
4. ✅ **Gratis y open source**
5. ✅ **Funciona perfecto con Laravel**

---

## 💻 Implementación con FullCalendar

### **1. Estructura de Datos para el Calendario**

El calendario necesita recibir las reservas en formato JSON:

```php
// app/Http/Controllers/ReservaController.php
public function getReservasCalendario(Request $request)
{
    $reservas = Reserva::with(['cancha', 'cliente'])
        ->where('estado', '!=', 'cancelada')
        ->get()
        ->map(function($reserva) {
            return [
                'id' => $reserva->id,
                'title' => $reserva->cancha->nombre . ' - ' . $reserva->cliente->nombre,
                'start' => $reserva->fecha . 'T' . $reserva->hora_inicio,
                'end' => $reserva->fecha . 'T' . $reserva->hora_fin,
                'color' => $this->getColorCancha($reserva->cancha_id),
                'extendedProps' => [
                    'cancha' => $reserva->cancha->nombre,
                    'cliente' => $reserva->cliente->nombre,
                    'telefono' => $reserva->cliente->telefono,
                ]
            ];
        });
    
    return response()->json($reservas);
}

private function getColorCancha($canchaId)
{
    $colores = [
        1 => '#3788d8', // Azul para Cancha 1
        2 => '#28a745', // Verde para Cancha 2
        3 => '#ffc107', // Amarillo para Cancha 3
    ];
    return $colores[$canchaId] ?? '#6c757d';
}
```

### **2. Vista del Calendario**

```html
<!-- resources/views/reservas/calendario.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Calendario de Reservas</h2>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="cambiarCancha(1)">
                    Cancha 1
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="cambiarCancha(2)">
                    Cancha 2
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" onclick="cambiarCancha(3)">
                    Cancha 3
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cambiarCancha(0)">
                    Todas
                </button>
            </div>
        </div>
    </div>
    
    <div id="calendar"></div>
</div>

<!-- Modal para crear/editar reserva -->
@include('reservas.modal')
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
let canchaFiltro = 0; // 0 = todas, 1, 2, 3 = cancha específica

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            // Cargar eventos desde Laravel
            fetch('/api/reservas-calendario?cancha=' + canchaFiltro)
                .then(response => response.json())
                .then(data => {
                    successCallback(data);
                })
                .catch(error => {
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            // Mostrar detalles de la reserva
            mostrarDetallesReserva(info.event);
        },
        dateClick: function(info) {
            // Click en fecha/hora para crear nueva reserva
            abrirModalCrearReserva(info.dateStr, info.allDay);
        },
        eventDrop: function(info) {
            // Si se arrastra un evento, actualizar
            actualizarHorarioReserva(info.event.id, info.event.start);
        },
        eventResize: function(info) {
            // Si se redimensiona, actualizar duración
            actualizarDuracionReserva(info.event.id, info.event.start, info.event.end);
        },
        slotMinTime: '08:00:00',
        slotMaxTime: '22:00:00',
        height: 'auto',
        allDaySlot: false
    });
    
    calendar.render();
    window.calendar = calendar; // Guardar referencia global
});

function cambiarCancha(canchaId) {
    canchaFiltro = canchaId;
    window.calendar.refetchEvents(); // Recargar eventos
}

function abrirModalCrearReserva(fecha, esTodoElDia) {
    // Abrir modal con formulario de creación
    $('#modalCrearReserva').modal('show');
    $('#fechaReserva').val(fecha);
    // ... más código
}

function mostrarDetallesReserva(evento) {
    // Mostrar información de la reserva
    alert('Cliente: ' + evento.extendedProps.cliente + '\n' +
          'Cancha: ' + evento.extendedProps.cancha + '\n' +
          'Teléfono: ' + evento.extendedProps.telefono);
}
</script>
@endpush
```

### **3. Ruta API**

```php
// routes/api.php
Route::get('/reservas-calendario', [ReservaController::class, 'getReservasCalendario']);
```

---

## 🎨 Vistas del Calendario

### **Vista Mensual**
```
┌─────────────────────────────────────────────────────────┐
│  ← Enero 2024 →                    [Mes] [Semana] [Día] │
├─────────────────────────────────────────────────────────┤
│  Lun    Mar    Mié    Jue    Vie    Sáb    Dom         │
├─────────────────────────────────────────────────────────┤
│         │      │      │      │      │      │            │
│   1     │  2   │  3   │  4   │  5   │  6   │     7      │
│         │      │      │      │      │      │            │
├─────────────────────────────────────────────────────────┤
│   8     │  9   │ 10   │ 11   │ 12   │ 13   │    14      │
│         │      │      │      │      │      │            │
├─────────────────────────────────────────────────────────┤
│  15     │ 16   │ 17   │ 18   │ 19   │ 20   │    21      │
│ [10-11] │      │      │      │      │      │            │
│  Juan   │      │      │      │      │      │            │
└─────────────────────────────────────────────────────────┘
```

### **Vista Semanal**
```
┌─────────────────────────────────────────────────────────┐
│  Semana 15-21 Enero                    [Mes] [Sem] [Día] │
├─────────────────────────────────────────────────────────┤
│  Hora  │ Lun 15 │ Mar 16 │ Mié 17 │ Jue 18 │ Vie 19    │
├─────────────────────────────────────────────────────────┤
│  08:00 │ [Libre]│ [Libre]│ [Libre]│ [Libre]│ [Libre]    │
│  09:00 │ [Libre]│ [Libre]│ [Libre]│ [Libre]│ [Libre]    │
│  10:00 │ [Juan] │ [Libre]│ [Libre]│ [Libre]│ [Libre]    │
│  11:00 │ [Juan] │ [Libre]│ [Libre]│ [Libre]│ [Libre]    │
│  12:00 │ [Libre]│ [Libre]│ [Libre]│ [Libre]│ [Libre]    │
│  14:00 │ [Libre]│ [María]│ [Libre]│ [Libre]│ [Libre]    │
│  15:00 │ [Libre]│ [María]│ [Libre]│ [Libre]│ [Libre]    │
└─────────────────────────────────────────────────────────┘
```

### **Vista Diaria**
```
┌─────────────────────────────────────────────────────────┐
│  Lunes 15 Enero                      [Mes] [Sem] [Día]   │
├─────────────────────────────────────────────────────────┤
│  Cancha 1                                              │
│  ────────────────────────────────────────────────────  │
│  08:00 ──────────────────────────── [Libre] [Reservar]│
│  09:00 ──────────────────────────── [Libre] [Reservar]│
│  10:00 ──────────────────────────── [RESERVADO]        │
│        Juan Pérez - 987654321        [Ver] [Editar]    │
│  11:00 ──────────────────────────── [RESERVADO]        │
│        Juan Pérez - 987654321        [Ver] [Editar]    │
│  12:00 ──────────────────────────── [Libre] [Reservar]│
│  13:00 ──────────────────────────── [Libre] [Reservar]│
│  14:00 ──────────────────────────── [Libre] [Reservar]│
└─────────────────────────────────────────────────────────┘
```

---

## 🚀 Ventajas del Calendario Dinámico

✅ **Visualización clara**: Se ve todo de un vistazo
✅ **Interactivo**: Click para crear, arrastrar para mover
✅ **Tiempo real**: Se actualiza automáticamente
✅ **Múltiples vistas**: Mes, semana, día
✅ **Filtros**: Por cancha, por fecha
✅ **Responsive**: Funciona en móvil y desktop

---

## 📦 Instalación Rápida

```bash
# 1. Instalar FullCalendar
npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/interaction

# 2. Compilar assets
npm run dev

# 3. O usar CDN (más rápido para empezar)
# Agregar en la vista: <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
```

---

## ✅ Resumen

**Sí, definitivamente puedes usar un calendario dinámico**, y **FullCalendar es la mejor opción** porque:

1. ✅ Es el estándar de la industria
2. ✅ Funciona perfecto con Laravel
3. ✅ Muy fácil de implementar
4. ✅ Se ve profesional
5. ✅ Tiene todo lo que necesitas

**¿Quieres que lo implemente en el proyecto?** Puedo crear:
- Las rutas API para el calendario
- El controlador con los métodos necesarios
- La vista con FullCalendar integrado
- Los filtros por cancha

¡Dime si procedo! 🚀

