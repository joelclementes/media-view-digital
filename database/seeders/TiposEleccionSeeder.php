<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoEleccion;

class TiposEleccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Presidente Municipal',],
            ['nombre' => 'Sindico',],
            ['nombre' => 'Regidor',],
            ['nombre' => 'Aspirante a candidatura independiente',],
            ['nombre' => 'Sin especificar',],
        ];

        foreach ($tipos as $tipo) {
            TipoEleccion::create($tipo);
        }
    }
}
