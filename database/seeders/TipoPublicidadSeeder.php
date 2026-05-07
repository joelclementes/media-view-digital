<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoPublicidad;

class TipoPublicidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Animada',],
            ['nombre' => 'Anuncios',],
            ['nombre' => 'Anuncios lumninosos',],
            ['nombre' => 'Bardas',],
            ['nombre' => 'Carteles',],
            ['nombre' => 'Espectaculares',],
            ['nombre' => 'Fija',],
            ['nombre' => 'Lonas',],
            ['nombre' => 'Mantas',],
            ['nombre' => 'Mobiliario urbano',],
            ['nombre' => 'Mupis',],
            ['nombre' => 'Parabuses',],
            ['nombre' => 'Propaganda ambulante',],
            ['nombre' => 'Publivallas',],
            ['nombre' => 'Unidades de servicio urbano',],
        ];

        foreach ($tipos as $tipo) {
            TipoPublicidad::create($tipo);
        }
    }
}
