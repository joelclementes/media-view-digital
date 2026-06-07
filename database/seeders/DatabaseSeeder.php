<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PartidosSeeder::class);
        $this->call(DistritosSeeder::class);
        $this->call(MunicipiosSeeder::class);
        // $this->call(LocalidadesSeeder::class);
        $this->call(PeriodosSeeder::class);
        $this->call(TamanoPublicacionSeeder::class);
        $this->call(TipoPublicidadSeeder::class);
        $this->call(GenerosSeeder::class);
        $this->call(ViolenciaTemaSeeder::class);
        $this->call(GenerosSujetoSeeder::class);
        $this->call(SujetosSeeder::class);
        $this->call(TiposEleccionSeeder::class);
        $this->call(PortalesInternetSeeder::class);
        $this->call(PrensaSeeder::class);
    }
}
