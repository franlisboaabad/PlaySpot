<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    /**
     * Una cancha tiene muchas reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * Reservas activas (no canceladas)
     */
    public function reservasActivas()
    {
        return $this->hasMany(Reserva::class)
            ->where('estado', '!=', 'cancelada');
    }
}
