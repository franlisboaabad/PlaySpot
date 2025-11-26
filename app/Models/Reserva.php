<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'cancha_id',
        'cliente_id',
        'user_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Una reserva pertenece a una cancha
     */
    public function cancha()
    {
        return $this->belongsTo(Cancha::class);
    }

    /**
     * Una reserva pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Una reserva fue creada por un usuario (dueño/admin)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Validar disponibilidad de un horario
     *
     * @param int $canchaId
     * @param string $fecha
     * @param string $horaInicio
     * @param string $horaFin
     * @param int|null $excluirId Reserva a excluir (para edición)
     * @return bool
     */
    public static function validarDisponibilidad($canchaId, $fecha, $horaInicio, $horaFin, $excluirId = null)
    {
        $query = self::where('cancha_id', $canchaId)
            ->where('fecha', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->where(function($q) use ($horaInicio, $horaFin) {
                $q->where(function($subQ) use ($horaInicio, $horaFin) {
                    // Nueva reserva empieza dentro de existente
                    $subQ->where('hora_inicio', '<=', $horaInicio)
                         ->where('hora_fin', '>', $horaInicio);
                })->orWhere(function($subQ) use ($horaInicio, $horaFin) {
                    // Nueva reserva termina dentro de existente
                    $subQ->where('hora_inicio', '<', $horaFin)
                         ->where('hora_fin', '>=', $horaFin);
                })->orWhere(function($subQ) use ($horaInicio, $horaFin) {
                    // Nueva reserva contiene completamente a existente
                    $subQ->where('hora_inicio', '>=', $horaInicio)
                         ->where('hora_fin', '<=', $horaFin);
                });
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->count() == 0;
    }
}
