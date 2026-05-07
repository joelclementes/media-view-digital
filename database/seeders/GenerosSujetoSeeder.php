<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneroSujeto;

class GenerosSujetoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generos = [
            ['nombre' => 'Masculino',],
            ['nombre' => 'Femenino',],
            ['nombre' => 'Sin especificar',],
        ];

        foreach ($generos as $genero) {
            GeneroSujeto::create($genero);
        }
    }
}
