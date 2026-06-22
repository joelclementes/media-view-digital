<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Joel Clemente Serrano',
            'email' => 'jclemente',
            'password' => bcrypt('123456789'),
        ])->assignRole('Super Usuario');

        User::create([
            'name' => 'Ángel Landa Villa',
            'email' => 'alanda',
            'password' => bcrypt('villa'),
        ])->assignRole('Super Usuario');

        User::create([
            'name' => 'Luis Enrique Sacramento González',
            'email' => 'lsacramento',
            'password' => bcrypt('123456789'),
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Usuario Capturista 1',
            'email' => 'capturista1',
            'password' => bcrypt('123456789'),
        ])->assignRole('Capturista');

        User::create([
            'name' => 'Usuario Capturista 2',
            'email' => 'capturista2',
            'password' => bcrypt('123456789'),
        ])->assignRole('Capturista');

        User::create([
            'name' => 'Usuario Consultor',
            'email' => 'consultor',
            'password' => bcrypt('123456789'),
        ])->assignRole('Consultor');
    }
}
