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
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Usuario Usuario',
            'email' => 'usuario',
            'password' => bcrypt('123456789'),
        ])->assignRole('Usuario');

        User::create([
            'name' => 'Usuario Consultor',
            'email' => 'consultor',
            'password' => bcrypt('123456789'),
        ])->assignRole('Consultor');
    }
}
