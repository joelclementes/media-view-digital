<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ViolenciaTema;

class ViolenciaTemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $temas = [
            ['nombre' => 'Sin discriminación',],
            ['nombre' => 'Violencia política contra las mujeres en razón de género',],
            ['nombre' => 'Presencia de roles o estereotipos de género',],
            ['nombre' => 'No usa lenguaje incluyente y no sexista',],
        ];

        foreach ($temas as $tema) {
            ViolenciaTema::create($tema);
        }
    }
}
