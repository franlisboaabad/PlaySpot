# Modelo de Base de Datos - Sistema de Reservas de Canchas

## 📋 Descripción del Sistema
Sistema de reservas en tiempo real para alquiler de canchas deportivas (1-3 canchas) de lunes a domingo con horarios específicos.

**Roles del Sistema:**
- **Usuarios (Users)**: Dueños/Administradores que gestionan las reservas
- **Clientes**: Personas que alquilan las canchas (no tienen acceso al sistema)

---

## 🗄️ Modelos y Tablas

### 1. **canchas** (Tabla: `canchas`)
Almacena la información de las canchas deportivas disponibles.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | Primary key, autoincrement |
| `nombre` | string(100) | Nombre de la cancha (ej: "Cancha 1", "Cancha Fútbol") |
| `descripcion` | text | Descripción opcional de la cancha |
| `activa` | boolean | Si la cancha está disponible para reservas (default: true) |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Fecha de actualización |

**Ejemplo de datos:**
- Cancha 1 (Fútbol)
- Cancha 2 (Vóley)
- Cancha 3 (Básquet)

---

### 2. **clientes** (Tabla: `clientes`)
Almacena la información de los clientes que alquilan las canchas.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | Primary key, autoincrement |
| `nombre` | string(100) | Nombre completo del cliente |
| `telefono` | string(20) | Teléfono de contacto |
| `email` | string(100) | Email (nullable, opcional) |
| `dni` | string(20) | DNI o documento de identidad (nullable) |
| `direccion` | text | Dirección (nullable) |
| `observaciones` | text | Notas adicionales sobre el cliente (nullable) |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Fecha de actualización |

**Índices:**
- `telefono` (para búsquedas rápidas)
- `email` (si se usa para búsquedas)

---

### 3. **reservas** (Tabla: `reservas`)
Almacena todas las reservas realizadas.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | Primary key, autoincrement |
| `cancha_id` | bigint | Foreign key → `canchas.id` |
| `cliente_id` | bigint | Foreign key → `clientes.id` (cliente que alquila) |
| `user_id` | bigint | Foreign key → `users.id` (usuario que creó la reserva) |
| `fecha` | date | Fecha de la reserva (ej: 2024-01-15) |
| `hora_inicio` | time | Hora de inicio (ej: 10:00:00) |
| `hora_fin` | time | Hora de fin (ej: 11:00:00) |
| `estado` | enum | Estado: `pendiente`, `confirmada`, `cancelada`, `completada` (default: `confirmada`) |
| `observaciones` | text | Notas adicionales sobre la reserva (nullable) |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Fecha de actualización |

**Índices importantes:**
- `cancha_id` + `fecha` + `hora_inicio` (para búsquedas rápidas)
- `cliente_id` + `fecha` (para ver reservas del cliente)
- `user_id` (para saber quién creó la reserva)

**Restricciones:**
- No puede haber dos reservas en la misma cancha con horarios que se solapen
- `hora_fin` debe ser mayor que `hora_inicio`

---

### 4. **users** (Tabla existente)
Usuarios del sistema (dueños/administradores) que gestionan las reservas.

**Campos relevantes:**
- `id`
- `name`
- `email`
- `password`
- Roles y permisos (Spatie Permission)

---

## 🔗 Relaciones Eloquent

### Modelo: `Cancha`
```php
// Una cancha tiene muchas reservas
public function reservas()
{
    return $this->hasMany(Reserva::class);
}

// Reservas activas (no canceladas)
public function reservasActivas()
{
    return $this->hasMany(Reserva::class)
        ->where('estado', '!=', 'cancelada');
}
```

### Modelo: `Cliente`
```php
// Un cliente tiene muchas reservas
public function reservas()
{
    return $this->hasMany(Reserva::class);
}

// Reservas activas del cliente
public function reservasActivas()
{
    return $this->hasMany(Reserva::class)
        ->where('estado', '!=', 'cancelada');
}
```

### Modelo: `Reserva`
```php
// Una reserva pertenece a una cancha
public function cancha()
{
    return $this->belongsTo(Cancha::class);
}

// Una reserva pertenece a un cliente
public function cliente()
{
    return $this->belongsTo(Cliente::class);
}

// Una reserva fue creada por un usuario (dueño/admin)
public function usuario()
{
    return $this->belongsTo(User::class, 'user_id');
}
```

### Modelo: `User` (extender)
```php
// Un usuario puede crear muchas reservas
public function reservasCreadas()
{
    return $this->hasMany(Reserva::class);
}
```

---

## 📊 Diagrama de Relaciones

```
┌─────────────┐
│    User     │ (Dueños/Administradores)
│             │
└──────┬──────┘
       │
       │ (crea)
       │
┌──────▼──────────┐         ┌──────────────┐         ┌─────────────┐
│    Reserva      │────────►│   Cliente    │         │   Cancha    │
│                 │         │              │         │             │
└─────────────────┘         └──────────────┘         └─────────────┘
       │                           │                        │
       │                           │                        │
       └───────────────────────────┴────────────────────────┘
```

**Relaciones:**
- User (1) ──< (N) Reserva (N) >── (1) Cliente
- User (1) ──< (N) Reserva (N) >── (1) Cancha
- Cliente (1) ──< (N) Reserva
- Cancha (1) ──< (N) Reserva

---

## 📊 Ejemplo de Consultas Importantes

### 1. Ver disponibilidad de una cancha en una fecha
```php
// Reservas de la Cancha 1 el 15 de enero de 2024
$reservas = Reserva::where('cancha_id', 1)
    ->where('fecha', '2024-01-15')
    ->where('estado', '!=', 'cancelada')
    ->with('cliente') // Cargar información del cliente
    ->orderBy('hora_inicio')
    ->get();
```

### 2. Verificar si un horario está disponible
```php
// Verificar si la cancha 1 está libre de 10:00 a 11:00 el 15/01/2024
$existeReserva = Reserva::where('cancha_id', 1)
    ->where('fecha', '2024-01-15')
    ->where('estado', '!=', 'cancelada')
    ->where(function($query) {
        $query->whereBetween('hora_inicio', ['10:00', '10:59'])
              ->orWhereBetween('hora_fin', ['10:01', '11:00'])
              ->orWhere(function($q) {
                  $q->where('hora_inicio', '<=', '10:00')
                    ->where('hora_fin', '>=', '11:00');
              });
    })
    ->exists();
```

### 3. Reservas de un cliente
```php
// Todas las reservas de un cliente específico
$reservasCliente = Cliente::find(1)
    ->reservas()
    ->with('cancha')
    ->orderBy('fecha', 'desc')
    ->orderBy('hora_inicio')
    ->get();
```

### 4. Reservas creadas por un usuario
```php
// Reservas creadas por el usuario autenticado
$misReservas = auth()->user()->reservasCreadas()
    ->with(['cancha', 'cliente'])
    ->orderBy('fecha', 'desc')
    ->orderBy('hora_inicio')
    ->get();
```

---

## ✅ Validaciones Importantes

1. **No solapamiento de horarios**: No puede haber dos reservas activas en la misma cancha con horarios que se crucen
2. **Fecha futura**: Las reservas deben ser para fechas futuras (o al menos no pasadas)
3. **Horario válido**: `hora_fin` > `hora_inicio`
4. **Cancha activa**: Solo se pueden reservar canchas que estén activas
5. **Cliente requerido**: Toda reserva debe tener un cliente asignado
6. **Usuario creador**: Toda reserva debe tener un usuario que la creó

---

## 🎯 Flujo de Trabajo

1. **Usuario (dueño)** entra al sistema
2. **Crea o selecciona un Cliente** (si no existe, lo crea)
3. **Selecciona una Cancha** disponible
4. **Selecciona Fecha y Horario**
5. **Sistema valida** que no haya conflicto de horarios
6. **Crea la Reserva** con el cliente seleccionado

---

## 🎯 Ventajas de este Modelo

✅ **Separación clara**: Usuarios (sistema) vs Clientes (alquileres)
✅ **Trazabilidad**: Se sabe quién creó cada reserva
✅ **Flexible**: Permite agregar más campos si es necesario (precio, descuentos, etc.)
✅ **Eficiente**: Índices optimizados para búsquedas rápidas
✅ **Tiempo real**: Fácil de consultar disponibilidad en tiempo real
✅ **Sin cruces**: La validación de solapamiento previene conflictos
✅ **Gestión de clientes**: Los usuarios pueden crear clientes al vuelo

---

## 📝 Próximos Pasos

1. Crear migraciones para `canchas`, `clientes` y `reservas`
2. Crear modelos Eloquent `Cancha`, `Cliente` y `Reserva`
3. Actualizar modelo `User` con la relación
4. Implementar validaciones de solapamiento
5. Crear controladores y vistas
6. Implementar búsqueda de clientes (por teléfono, nombre, etc.)
7. Implementar API para consultas en tiempo real (opcional)
