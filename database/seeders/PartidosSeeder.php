<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PartidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = database_path('seeders/cat_partidos_ant.sql');

        // Verificar si el archivo existe
        if (!File::exists($rutaArchivo)) {
            $this->command->error("El archivo $rutaArchivo no existe.");
            return;
        }

        // Leer el contenido del archivo .sql
        $sql = File::get($rutaArchivo);

        // Ejecutar las consultas SQL
        DB::unprepared($sql);

        // Obtengo los registros de la tabla cat_partidos_ant
        $partidosAntiguos = DB::table('cat_partidos_ant')->get();

        // Itero los registros y los clono en la tabla distritos
        foreach ($partidosAntiguos as $partido) {

            DB::table('partidos')->insert([
                'nombre' => trim($partido->nombre),
                'siglas' => trim($partido->siglas),
                'logo' => trim($partido->logo),
                'tipo' => trim($partido->tipo),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::statement('DROP TABLE IF EXISTS cat_partidos_ant;');
    }
}
