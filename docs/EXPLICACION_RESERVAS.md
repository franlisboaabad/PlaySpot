# 📋 Explicación del Sistema de Reservas

## 🎯 ¿Cómo Funciona una Reserva?

### Flujo Completo de Creación de Reserva

```
1. Usuario (dueño) entra al sistema
   ↓
2. Va a "Crear Reserva"
   ↓
3. Busca o Crea Cliente
   ├─ Opción A: Busca cliente existente (por teléfono/nombre)
   └─ Opción B: Crea nuevo cliente rápido
   ↓
4. Selecciona Cancha (1, 2 o 3)
   ↓
5. Selecciona Fecha (calendario)
   ↓
6. Selecciona Horario (ej: 10:00 - 11:00)
   ↓
7. Sistema VALIDA disponibilidad
   ├─ ¿Hay conflicto? → Muestra error
   └─ ¿Está libre? → Crea la reserva
   ↓
8. Reserva creada exitosamente
```

---

## 🔍 Paso a Paso Detallado

### **Paso 1: Usuario Accede al Sistema**
El dueño/administrador inicia sesión con su cuenta de usuario.

### **Paso 2: Crear Nueva Reserva**
El usuario hace clic en "Nueva Reserva" o "Alquilar Cancha"

### **Paso 3: Seleccionar o Crear Cliente**

**Opción A: Buscar Cliente Existente**
```
Campo de búsqueda: [________________] 🔍
Buscar por: Teléfono o Nombre

Ejemplo:
- Escribe "987654321" → Encuentra "Juan Pérez"
- O escribe "Juan" → Muestra lista de clientes con ese nombre
```

**Opción B: Crear Cliente Rápido**
```
Si no existe, aparece botón "Crear Nuevo Cliente"
Formulario rápido:
- Nombre: [________________]
- Teléfono: [________________] (obligatorio)
- Email: [________________] (opcional)
- [Guardar y Continuar]
```

### **Paso 4: Seleccionar Cancha**
```
☐ Cancha 1 (Fútbol)
☐ Cancha 2 (Vóley)  
☐ Cancha 3 (Básquet)
```

### **Paso 5: Seleccionar Fecha**
```
Calendario mostrando:
- Fechas pasadas: Deshabilitadas
- Fecha seleccionada: Resaltada
- Fecha actual: Marcada
```

### **Paso 6: Seleccionar Horario**

**Opción 1: Horarios Predefinidos (Recomendado)**
```
Horarios disponibles para el día seleccionado:

Cancha 1 - Lunes 15/01/2024:
[08:00-09:00] [09:00-10:00] [10:00-11:00] [11:00-12:00]
[14:00-15:00] [15:00-16:00] [16:00-17:00] [17:00-18:00]
[18:00-19:00] [19:00-20:00] [20:00-21:00]

Horarios ocupados aparecen en rojo/gris y deshabilitados
Horarios libres aparecen en verde y son clickeables
```

**Opción 2: Horario Personalizado**
```
Hora Inicio: [10:00] ──── Hora Fin: [11:00]
```

### **Paso 7: Validación Automática**

El sistema verifica en tiempo real:

```php
// Pseudocódigo de validación
function validarDisponibilidad($cancha_id, $fecha, $hora_inicio, $hora_fin) {
    
    // 1. Verificar que la cancha esté activa
    if (!cancha_activa($cancha_id)) {
        return "La cancha no está disponible";
    }
    
    // 2. Verificar que no haya reservas que se solapen
    $conflictos = buscar_reservas_conflictivas(
        $cancha_id, 
        $fecha, 
        $hora_inicio, 
        $hora_fin
    );
    
    if ($conflictos->count() > 0) {
        return "El horario ya está ocupado";
    }
    
    // 3. Verificar que hora_fin > hora_inicio
    if ($hora_fin <= $hora_inicio) {
        return "La hora de fin debe ser mayor a la de inicio";
    }
    
    // 4. Todo OK
    return true;
}
```

### **Paso 8: Guardar Reserva**

Si todo está bien, se crea la reserva:

```php
Reserva::create([
    'cancha_id' => 1,
    'cliente_id' => 5,
    'user_id' => auth()->id(), // Usuario que creó la reserva
    'fecha' => '2024-01-15',
    'hora_inicio' => '10:00:00',
    'hora_fin' => '11:00:00',
    'estado' => 'confirmada',
    'observaciones' => 'Cliente pagó en efectivo'
]);
```

---

## 🎨 Visualización en Tiempo Real

### Vista de Calendario/Semana

```
Lunes 15/01    Martes 16/01    Miércoles 17/01
─────────────  ─────────────    ──────────────
Cancha 1       Cancha 1        Cancha 1
08:00 [Libre]  08:00 [Libre]   08:00 [Libre]
09:00 [Libre]  09:00 [Libre]   09:00 [Libre]
10:00 [Juan]   10:00 [Libre]   10:00 [Libre]
11:00 [Juan]   11:00 [Libre]   11:00 [Libre]
12:00 [Libre]  12:00 [Libre]   12:00 [Libre]
14:00 [Libre]  14:00 [María]   14:00 [Libre]
15:00 [Libre]  15:00 [María]   15:00 [Libre]
```

### Vista de Día (Detallada)

```
Cancha 1 - Lunes 15 de Enero 2024

08:00 ──────────────────────────── [Libre] [Reservar]
09:00 ──────────────────────────── [Libre] [Reservar]
10:00 ──────────────────────────── [RESERVADO - Juan Pérez]
11:00 ──────────────────────────── [RESERVADO - Juan Pérez]
12:00 ──────────────────────────── [Libre] [Reservar]
13:00 ──────────────────────────── [Libre] [Reservar]
14:00 ──────────────────────────── [Libre] [Reservar]
15:00 ──────────────────────────── [Libre] [Reservar]
```

---

## 🔐 Validación de Conflictos (Lo Más Importante)

### ¿Cómo se evita que dos reservas se crucen?

**Ejemplo de Conflicto:**
```
Reserva Existente: 10:00 - 11:00
Nueva Reserva:     10:30 - 11:30  ❌ CONFLICTO
```

**Lógica de Validación:**

```php
// Verificar si hay solapamiento de horarios
function hayConflicto($hora_inicio_existente, $hora_fin_existente, 
                      $hora_inicio_nueva, $hora_fin_nueva) {
    
    // Caso 1: Nueva reserva empieza dentro de la existente
    if ($hora_inicio_nueva >= $hora_inicio_existente && 
        $hora_inicio_nueva < $hora_fin_existente) {
        return true; // CONFLICTO
    }
    
    // Caso 2: Nueva reserva termina dentro de la existente
    if ($hora_fin_nueva > $hora_inicio_existente && 
        $hora_fin_nueva <= $hora_fin_existente) {
        return true; // CONFLICTO
    }
    
    // Caso 3: Nueva reserva contiene completamente a la existente
    if ($hora_inicio_nueva <= $hora_inicio_existente && 
        $hora_fin_nueva >= $hora_fin_existente) {
        return true; // CONFLICTO
    }
    
    return false; // NO HAY CONFLICTO
}
```

**Ejemplos Visuales:**

```
✅ PERMITIDO:
[10:00-11:00] [11:00-12:00]  (Consecutivos, no se tocan)

❌ CONFLICTO:
[10:00-11:00] [10:30-11:30]  (Se solapan)

❌ CONFLICTO:
[10:00-11:00] [09:30-10:30]  (Se solapan)

✅ PERMITIDO:
[10:00-11:00] [12:00-13:00]  (Separados, no hay conflicto)
```

---

## 💻 Implementación Técnica

### Modelo Reserva (Validaciones)

```php
class Reserva extends Model
{
    protected $fillable = [
        'cancha_id',
        'cliente_id',
        'user_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'observaciones'
    ];

    // Validación personalizada
    public static function validarDisponibilidad($cancha_id, $fecha, $hora_inicio, $hora_fin, $excluir_id = null)
    {
        // Buscar reservas que se solapen
        $query = self::where('cancha_id', $cancha_id)
            ->where('fecha', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->where(function($q) use ($hora_inicio, $hora_fin) {
                $q->where(function($subQ) use ($hora_inicio, $hora_fin) {
                    // Nueva reserva empieza dentro de existente
                    $subQ->where('hora_inicio', '<=', $hora_inicio)
                         ->where('hora_fin', '>', $hora_inicio);
                })->orWhere(function($subQ) use ($hora_inicio, $hora_fin) {
                    // Nueva reserva termina dentro de existente
                    $subQ->where('hora_inicio', '<', $hora_fin)
                         ->where('hora_fin', '>=', $hora_fin);
                })->orWhere(function($subQ) use ($hora_inicio, $hora_fin) {
                    // Nueva reserva contiene completamente a existente
                    $subQ->where('hora_inicio', '>=', $hora_inicio)
                         ->where('hora_fin', '<=', $hora_fin);
                });
            });

        if ($excluir_id) {
            $query->where('id', '!=', $excluir_id);
        }

        return $query->count() == 0;
    }
}
```

### Controlador (Crear Reserva)

```php
public function store(Request $request)
{
    // Validar datos
    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'cancha_id' => 'required|exists:canchas,id',
        'fecha' => 'required|date|after_or_equal:today',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ]);

    // Verificar disponibilidad
    if (!Reserva::validarDisponibilidad(
        $request->cancha_id,
        $request->fecha,
        $request->hora_inicio,
        $request->hora_fin
    )) {
        return back()->withErrors([
            'horario' => 'El horario seleccionado ya está ocupado'
        ]);
    }

    // Crear reserva
    Reserva::create([
        'cancha_id' => $request->cancha_id,
        'cliente_id' => $request->cliente_id,
        'user_id' => auth()->id(),
        'fecha' => $request->fecha,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin' => $request->hora_fin,
        'estado' => 'confirmada',
        'observaciones' => $request->observaciones,
    ]);

    return redirect()->route('reservas.index')
        ->with('success', 'Reserva creada exitosamente');
}
```

---

## 📱 Interfaz de Usuario (Cómo se Vería)

### Pantalla de Crear Reserva

```
┌─────────────────────────────────────────────────┐
│  NUEVA RESERVA                                  │
├─────────────────────────────────────────────────┤
│                                                  │
│  Cliente:                                       │
│  [Buscar por teléfono o nombre...] 🔍          │
│  ┌─────────────────────────────────────────┐   │
│  │ Juan Pérez - 987654321                  │   │
│  │ [Seleccionar] [Crear Nuevo Cliente]     │   │
│  └─────────────────────────────────────────┘   │
│                                                  │
│  Cancha:                                        │
│  ○ Cancha 1 (Fútbol)                           │
│  ○ Cancha 2 (Vóley)                            │
│  ○ Cancha 3 (Básquet)                          │
│                                                  │
│  Fecha: [📅 15/01/2024]                        │
│                                                  │
│  Horario:                                       │
│  ┌─────────────────────────────────────────┐   │
│  │ 08:00-09:00 [Libre] [Seleccionar]      │   │
│  │ 09:00-10:00 [Libre] [Seleccionar]      │   │
│  │ 10:00-11:00 [Ocupado - Juan Pérez]     │   │
│  │ 11:00-12:00 [Libre] [Seleccionar]      │   │
│  │ 14:00-15:00 [Libre] [Seleccionar]      │   │
│  └─────────────────────────────────────────┘   │
│                                                  │
│  Observaciones:                                 │
│  [________________________________]             │
│                                                  │
│  [Cancelar]              [Crear Reserva]       │
└─────────────────────────────────────────────────┘
```

---

## ✅ Resumen

**¿Cómo se realiza una reserva?**

1. **Usuario busca/crea cliente** → Selecciona o crea al vuelo
2. **Selecciona cancha** → De las 1-3 disponibles
3. **Elige fecha** → Del calendario
4. **Elige horario** → De los disponibles (sistema muestra ocupados en rojo)
5. **Sistema valida** → Verifica que no haya conflicto
6. **Guarda reserva** → Con toda la información

**Lo más importante:**
- ✅ Validación automática de conflictos
- ✅ Visualización en tiempo real de disponibilidad
- ✅ Creación rápida de clientes
- ✅ Trazabilidad (saber quién creó cada reserva)

¿Te queda claro? ¿Quieres que proceda a crear las migraciones y modelos?

