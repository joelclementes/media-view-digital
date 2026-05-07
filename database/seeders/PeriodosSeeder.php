<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Periodo;

class PeriodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodos = [
            ['nombre' => 'Previo a la precampaña',],
            ['nombre' => 'Precampaña',],
            ['nombre' => 'Previo a la campaña',],
            ['nombre' => 'Campaña',],
            ['nombre' => 'Veda electoral',],
            ['nombre' => 'Jornada electoral',],
            ['nombre' => 'Periodo de apoyo ciudadano',],
            ['nombre' => 'Intercampaña',],
        ];

        foreach ($periodos as $periodo) {
            Periodo::create($periodo);
        }
    }
}
