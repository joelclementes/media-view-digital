<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genero;

class GenerosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generos = [
            [
                'nombre' => 'Noticia',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Crónica',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Reportaje',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Entrevista',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Editorial',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Fotografía',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Caricatura',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Columna',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Opinión de electores',
                'medio' => 'Electrónico',
            ],
            [
                'nombre' => 'Cintillo',
                'medio' => 'Impreso',
            ],
            [
                'nombre' => 'Inserción pagada',
                'medio' => 'Impreso',
            ],
            [
                'nombre' => 'N/A',
                'medio' => 'N/A',
            ],
        ];

        foreach ($generos as $genero) {
            Genero::create($genero);
        }
    }
}
