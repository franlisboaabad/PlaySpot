<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'dni',
        'direccion',
        'observaciones',
    ];

    /**
     * Un cliente tiene muchas reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * Reservas activas del cliente (no canceladas)
     */
    public function reservasActivas()
    {
        return $this->hasMany(Reserva::class)
            ->where('estado', '!=', 'cancelada');
    }
}
