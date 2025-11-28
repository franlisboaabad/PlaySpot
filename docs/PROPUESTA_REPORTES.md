# 📊 Propuesta de Reportes - Sistema de Reservas de Canchas

## 📋 Resumen Ejecutivo

El sistema de reservas de canchas deportivas puede generar diversos reportes útiles para la gestión y análisis del negocio. A continuación se presenta una propuesta completa de reportes organizados por categorías.

---

## 🎯 1. REPORTES DE OPERACIONES

### 1.1 Reporte de Reservas por Período
**Descripción:** Muestra todas las reservas en un rango de fechas seleccionado.

**Datos a mostrar:**
- Fecha y hora de reserva
- Cancha reservada
- Cliente (nombre, teléfono)
- Estado de la reserva
- Usuario que creó la reserva
- Duración (horas)
- Observaciones

**Filtros:**
- Fecha desde/hasta
- Cancha (todas o específica)
- Estado (todos, confirmada, pendiente, cancelada, completada)
- Cliente (opcional)

**Formato:** Tabla con opción de exportar a PDF/Excel

---

### 1.2 Reporte de Ocupación de Canchas
**Descripción:** Análisis de ocupación por cancha en un período determinado.

**Datos a mostrar:**
- Nombre de la cancha
- Total de horas reservadas
- Porcentaje de ocupación
- Horas disponibles
- Reservas totales
- Horas promedio por reserva
- Días con mayor ocupación

**Visualización:**
- Gráfico de barras por cancha
- Gráfico de líneas temporal
- Tabla comparativa

---

### 1.3 Reporte de Horarios Más Solicitados
**Descripción:** Identifica los horarios más populares.

**Datos a mostrar:**
- Rango horario (ej: 08:00-10:00, 10:00-12:00)
- Cantidad de reservas
- Cancha más solicitada en ese horario
- Día de la semana más popular
- Porcentaje del total

**Visualización:**
- Gráfico de barras horizontales
- Heatmap (mapa de calor) de horarios

---

### 1.4 Reporte de Reservas por Día de la Semana
**Descripción:** Análisis de la demanda según el día de la semana.

**Datos a mostrar:**
- Día de la semana (Lunes a Domingo)
- Total de reservas
- Cancha más solicitada
- Horario pico
- Promedio de reservas por día
- Comparativa semana a semana

**Visualización:**
- Gráfico de barras
- Comparativa con períodos anteriores

---

## 💰 2. REPORTES FINANCIEROS

> **Nota:** Estos reportes requieren agregar un campo de `precio` o `monto` a la tabla de reservas en el futuro.

### 2.1 Reporte de Ingresos por Período
**Descripción:** Ingresos generados en un período específico.

**Datos a mostrar:**
- Fecha
- Cancha
- Cliente
- Monto de la reserva
- Estado (solo confirmadas/completadas)
- Total acumulado
- Promedio diario/semanal/mensual

**Filtros:**
- Fecha desde/hasta
- Cancha
- Estado de pago (si se implementa)

**Visualización:**
- Gráfico de líneas (evolución)
- Gráfico de torta (por cancha)

---

### 2.2 Reporte de Ingresos por Cancha
**Descripción:** Comparativa de ingresos generados por cada cancha.

**Datos a mostrar:**
- Nombre de cancha
- Total de ingresos
- Cantidad de reservas
- Ingreso promedio por reserva
- Porcentaje del total de ingresos
- Ocupación en horas

**Visualización:**
- Gráfico de barras
- Gráfico de torta

---

### 2.3 Reporte de Reservas Canceladas (Pérdidas)
**Descripción:** Análisis de cancelaciones y su impacto.

**Datos a mostrar:**
- Cantidad de cancelaciones
- Motivo (si se agrega campo)
- Fecha de cancelación
- Tiempo antes de la reserva cancelada
- Valor perdido (si hay precio)
- Tasa de cancelación (%)
- Tendencia de cancelaciones

**Visualización:**
- Gráfico de barras temporal
- Análisis de tendencias

---

## 👥 3. REPORTES DE CLIENTES

### 3.1 Reporte de Clientes Frecuentes
**Descripción:** Identifica los clientes más recurrentes.

**Datos a mostrar:**
- Nombre del cliente
- Teléfono y email
- Total de reservas
- Última reserva
- Cancha preferida
- Horario preferido
- Total de horas reservadas
- Ranking

**Filtros:**
- Período de análisis
- Cantidad mínima de reservas
- Top N (10, 20, 50)

**Formato:** Tabla con opción de exportar

---

### 3.2 Reporte de Nuevos Clientes
**Descripción:** Clientes registrados en un período.

**Datos a mostrar:**
- Nombre
- Fecha de registro
- Total de reservas realizadas
- Última reserva
- Datos de contacto
- Estado (activo/inactivo)

**Filtros:**
- Fecha de registro desde/hasta
- Con/sin reservas

---

### 3.3 Reporte de Clientes por Cancha
**Descripción:** Distribución de clientes por cancha preferida.

**Datos a mostrar:**
- Cancha
- Lista de clientes (top N)
- Cantidad de reservas por cliente
- Total de clientes únicos
- Cliente más frecuente

---

### 3.4 Reporte de Clientes Inactivos
**Descripción:** Clientes que no han realizado reservas en un tiempo determinado.

**Datos a mostrar:**
- Nombre del cliente
- Última reserva
- Días sin reservar
- Total de reservas históricas
- Datos de contacto
- Cancha preferida anteriormente

**Filtros:**
- Días sin reservar (ej: más de 30, 60, 90 días)
- Último contacto

**Uso:** Marketing, reactivación de clientes

---

## 📈 4. REPORTES ANALÍTICOS

### 4.1 Reporte de Tendencia de Reservas
**Descripción:** Evolución de reservas en el tiempo.

**Datos a mostrar:**
- Fecha/período
- Cantidad de reservas
- Comparativa con período anterior
- Crecimiento porcentual
- Tendencia (creciente/decreciente)
- Proyección (opcional)

**Visualización:**
- Gráfico de líneas temporal
- Indicadores de crecimiento

---

### 4.2 Reporte de Análisis de Ocupación Detallado
**Descripción:** Análisis profundo de la ocupación de canchas.

**Datos a mostrar:**
- Cancha
- Horas totales disponibles (en el período)
- Horas reservadas
- Horas libres
- Porcentaje de ocupación
- Horas pico
- Horas valle (baja ocupación)
- Comparativa con período anterior

**Visualización:**
- Gráfico de barras apiladas
- Gráfico de radar (comparativa de canchas)

---

### 4.3 Reporte de Análisis de Cancelaciones
**Descripción:** Análisis detallado de cancelaciones.

**Datos a mostrar:**
- Fecha de cancelación
- Fecha de reserva cancelada
- Tiempo de anticipación
- Cancha
- Cliente
- Razón (si existe)
- Patrones de cancelación

**Visualización:**
- Gráfico de barras por motivo
- Análisis de tiempo de anticipación

---

### 4.4 Reporte Comparativo Mensual
**Descripción:** Comparación de meses o períodos.

**Datos a mostrar:**
- Mes/Período
- Total de reservas
- Reservas por cancha
- Reservas por día de semana
- Nuevos clientes
- Cancelaciones
- Ocupación promedio
- Crecimiento respecto al período anterior

**Visualización:**
- Tabla comparativa
- Gráficos de barras agrupadas

---

## 📅 5. REPORTES DE PROGRAMACIÓN

### 5.1 Reporte de Reservas Futuras
**Descripción:** Reservas confirmadas y pendientes a futuro.

**Datos a mostrar:**
- Fecha
- Hora
- Cancha
- Cliente
- Estado
- Días hasta la reserva
- Contacto del cliente

**Filtros:**
- Fecha desde
- Cancha
- Estado
- Días a futuro (próximos 7, 15, 30 días)

**Formato:** Calendario visual y/o tabla

---

### 5.2 Reporte de Disponibilidad
**Descripción:** Horarios disponibles en un período.

**Datos a mostrar:**
- Fecha
- Cancha
- Horarios disponibles
- Horarios ocupados
- Porcentaje de disponibilidad

**Visualización:**
- Calendario con colores
- Tabla de disponibilidad

---

### 5.3 Reporte de Reservas por Usuario
**Descripción:** Reservas creadas por cada usuario/administrador.

**Datos a mostrar:**
- Usuario
- Total de reservas creadas
- Reservas confirmadas
- Reservas canceladas
- Período activo
- Reservas por mes
- Promedio de reservas por día

---

## 📊 6. REPORTES DE GESTIÓN

### 6.1 Dashboard Ejecutivo
**Descripción:** Resumen ejecutivo con KPIs principales.

**Indicadores a mostrar:**
- Total de reservas (hoy, semana, mes)
- Ocupación promedio
- Clientes activos
- Reservas por cancha
- Tendencia de crecimiento
- Horarios más solicitados
- Tasa de cancelación

**Visualización:**
- Tarjetas de métricas
- Gráficos de tendencias
- Tablas resumen

---

### 6.2 Reporte de Actividad del Sistema
**Descripción:** Actividad general del sistema en un período.

**Datos a mostrar:**
- Fecha
- Acción realizada (crear, editar, cancelar reserva)
- Usuario que realizó la acción
- Detalle de la acción
- Cliente afectado
- Cancha afectada

**Uso:** Auditoría, seguimiento de operaciones

---

### 6.3 Reporte de Rendimiento por Cancha
**Descripción:** Rendimiento y productividad de cada cancha.

**Datos a mostrar:**
- Cancha
- Horas de operación
- Horas reservadas
- Horas disponibles
- Tasa de ocupación
- Reservas totales
- Ingresos (si aplica)
- Clientes únicos

---

## 📄 7. REPORTES IMPRIMIBLES

### 7.1 Listado de Reservas (PDF)
**Descripción:** Reporte impreso para registro físico.

**Datos:**
- Todas las reservas del día/semana
- Formato tabla compacto
- Firma y sello (opcional)

---

### 7.2 Recibo/Comprobante de Reserva
**Descripción:** Documento individual de reserva.

**Datos:**
- Datos del cliente
- Detalles de la reserva
- Cancha
- Fecha y hora
- Monto (si aplica)
- Estado

---

## 🔧 IMPLEMENTACIÓN SUGERIDA

### Prioridad Alta (Implementar primero)
1. ✅ **Reporte de Reservas por Período** - Básico y esencial
2. ✅ **Reporte de Ocupación de Canchas** - Para optimización
3. ✅ **Reporte de Clientes Frecuentes** - Marketing y fidelización
4. ✅ **Dashboard Ejecutivo** - Vista general

### Prioridad Media
5. **Reporte de Horarios Más Solicitados** - Optimización de horarios
6. **Reporte de Reservas por Día de la Semana** - Análisis de demanda
7. **Reporte de Tendencia de Reservas** - Análisis temporal
8. **Reporte de Clientes Inactivos** - Reactivación

### Prioridad Baja (Futuro)
9. **Reportes Financieros** - Requiere campo de precio
10. **Reporte de Análisis de Cancelaciones** - Requiere campo de motivo
11. **Reportes de Auditoría** - Sistema de logs

---

## 📋 ESTRUCTURA TÉCNICA SUGERIDA

### Controlador de Reportes
```
app/Http/Controllers/ReporteController.php
```

### Métodos sugeridos:
- `index()` - Lista de reportes disponibles
- `reservasPorPeriodo()` - Reporte de reservas
- `ocupacionCanchas()` - Reporte de ocupación
- `clientesFrecuentes()` - Clientes recurrentes
- `exportarPdf()` - Exportación a PDF
- `exportarExcel()` - Exportación a Excel

### Vistas
```
resources/views/admin/reportes/
├── index.blade.php (Lista de reportes)
├── reservas-periodo.blade.php
├── ocupacion.blade.php
├── clientes-frecuentes.blade.php
└── ...
```

### Librerías recomendadas:
- **PDF:** `barryvdh/laravel-dompdf` o `barryvdh/laravel-snappy`
- **Excel:** `maatwebsite/excel`
- **Gráficos:** Chart.js (ya incluido en AdminLTE) o Laravel Charts

---

## 📊 FORMATOS DE SALIDA

### Formato Web
- Vistas HTML responsivas
- Gráficos interactivos (Chart.js)
- Tablas con paginación y filtros
- Opción de descarga

### Formato PDF
- Reportes imprimibles
- Formato profesional
- Logos y encabezados personalizables

### Formato Excel
- Datos exportables
- Hojas múltiples
- Filtros y formato

---

## 💡 RECOMENDACIONES

1. **Implementar gradualmente:** Comenzar con reportes básicos y agregar complejidad según necesidad.

2. **Optimizar consultas:** Usar índices en la base de datos y cachear reportes que no cambian frecuentemente.

3. **Personalización:** Permitir que el usuario seleccione columnas y filtros según su necesidad.

4. **Exportación:** Siempre ofrecer opción de exportar a PDF o Excel.

5. **Visualización:** Usar gráficos cuando sea posible para facilitar el análisis.

6. **Filtros:** Implementar filtros flexibles para permitir análisis diversos.

---

## 🎯 CONCLUSIÓN

El sistema cuenta con datos suficientes para generar reportes valiosos que ayuden en:
- **Toma de decisiones** estratégicas
- **Optimización** de recursos
- **Análisis de rentabilidad** (con precios)
- **Marketing** y fidelización de clientes
- **Planificación** de horarios y disponibilidad

Los reportes propuestos pueden implementarse de forma incremental, comenzando con los más esenciales y agregando complejidad según las necesidades del negocio.

