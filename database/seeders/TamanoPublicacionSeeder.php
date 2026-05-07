<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TamanoPublicacion;

class TamanoPublicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tamanos = [
            ['nombre' => '3/4 de plana',],
            ['nombre' => '1/2 de plana',],
            ['nombre' => '1/4 de plana',],
            ['nombre' => '1/8 de plana',],
        ];

        foreach ($tamanos as $tamano) {
            TamanoPublicacion::create($tamano);
        }
    }
}
