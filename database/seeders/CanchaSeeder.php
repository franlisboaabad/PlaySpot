<?php

namespace Database\Seeders;

use App\Models\Cancha;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CanchaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Cancha::create([
            'nombre' => 'Cancha 1',
            'descripcion' => 'Cancha principal de fútbol',
            'activa' => true,
        ]);

        Cancha::create([
            'nombre' => 'Cancha 2',
            'descripcion' => 'Cancha de vóley',
            'activa' => true,
        ]);

        Cancha::create([
            'nombre' => 'Cancha 3',
            'descripcion' => 'Cancha de básquet',
            'activa' => true,
        ]);
    }
}
