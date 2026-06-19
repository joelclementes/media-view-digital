<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sujeto;

class SujetosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sujetos = [
            [
                'nombre' => 'N/A',
            ],
            [
                'nombre' => 'ALBERTO GARCÍA ROMO',
                'genero_id' => 1,
                'municipio_id' => 173,
                'partido_id' => 19,
            ],
            [
                'nombre' => 'MARÍA FERNANDA LÓPEZ SÁNCHEZ',
                'genero_id' => 2,
                'municipio_id' => 155,
                'partido_id' => 12,
            ],
            [
                'nombre' => 'JOSÉ LUIS MARTÍNEZ HERNÁNDEZ',
                'genero_id' => 1,
                'municipio_id' => 16,
                'partido_id' => 5,
            ],
            [
                'nombre' => 'ANA PATRICIA SÁNCHEZ RAMÍREZ',
                'genero_id' => 2,
                'municipio_id' => 173,
                'partido_id' => 19,
            ],
            [
                'nombre' => 'CARLOS ALBERTO RAMÍREZ TORRES',
                'genero_id' => 1,
                'municipio_id' => 169,
                'partido_id' => 19,
            ],
            [
                'nombre' => 'LAURA CRISTINA TORRES FLORES',
                'genero_id' => 2,
                'municipio_id' => 56,
                'partido_id' => 3,
            ],
            [
                'nombre' => 'JAVIER IGNACIO FLORES CASTRO',
                'genero_id' => 1,
                'municipio_id' => 156,
                'partido_id' => 1,
            ],
            [
                'nombre' => 'VERÓNICA ELIZABETH CASTRO REYES',
                'genero_id' => 2,
                'municipio_id' => 54,
                'partido_id' => 18,
            ],
            [
                'nombre' => 'RICARDO DANIEL ORTIZ MORALES',
                'genero_id' => 1,
                'municipio_id' => 151,
                'partido_id' => 20,
            ],
            [
                'nombre' => 'SANDRA PATRICIA REYES DÍAZ',
                'genero_id' => 2,
                'municipio_id' => 199,
                'partido_id' => 23,
            ],
            [
                'nombre' => 'MIGUEL ÁNGEL MORALES CRUZ',
                'genero_id' => 1,
                'municipio_id' => 141,
                'partido_id' => 23,
            ],
            [
                'nombre' => 'PATRICIA GUADALUPE DÍAZ GÓMEZ',
                'genero_id' => 2,
                'municipio_id' => 154,
                'partido_id' => 19,
            ],
            [
                'nombre' => 'FERNANDO JAVIER HERRERA VARGAS',
                'genero_id' => 1,
                'municipio_id' => 98,
                'partido_id' => 19,
            ],
            [
                'nombre' => 'ALEJANDRA BEATRIZ CRUZ MENDOZA',
                'genero_id' => 2,
                'municipio_id' => 118,
                'partido_id' => 19,
            ],
            [
                'nombre' => 'ROBERTO CARLOS VARGAS GUZMÁN',
                'genero_id' => 1,
                'municipio_id' => 42,
                'partido_id' => 11,
            ],
            [
                'nombre' => 'CARMEN SOFÍA GUZMÁN AGUILAR',
                'genero_id' => 2,
                'municipio_id' => 191,
                'partido_id' => 8,
            ],
            [
                'nombre' => 'GUILLERMO ANTONIO MENDOZA ROMERO',
                'genero_id' => 1,
                'municipio_id' => 203,
                'partido_id' => 10,
            ],
            [
                'nombre' => 'ADRIANA MARISOL AGUILAR CASTILLO',
                'genero_id' => 2,
                'municipio_id' => 93,
                'partido_id' => 3,
            ],
            [
                'nombre' => 'SERGIO ARMANDO CASTILLO RIVERA',
                'genero_id' => 1,
                'municipio_id' => 76,
                'partido_id' => 9,
            ],
            [
                'nombre' => 'MONICA LIZBETH RIVERA ORTEGA',
                'genero_id' => 2,
                'municipio_id' => 155,
                'partido_id' => 12,
            ],
            [
                'nombre' => 'RAÚL ALEJANDRO PÉREZ NAVARRO',
                'genero_id' => 1,
                'municipio_id' => 16,
                'partido_id' => 5,
            ],
            [
                'nombre' => 'DANIELA SOFÍA JIMÉNEZ VEGA',
                'genero_id' => 2,
                'municipio_id' => 173,
                'partido_id' => 19,
            ],
        ];
        foreach ($sujetos as $sujeto) {
            Sujeto::create($sujeto);
        }
    }
}
