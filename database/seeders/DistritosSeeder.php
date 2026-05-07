<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DistritosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = database_path('seeders/cat_distritos_ant.sql');

        // Verificar si el archivo existe
        if (!File::exists($rutaArchivo)) {
            $this->command->error("El archivo $rutaArchivo no existe.");
            return;
        }

        // Leer el contenido del archivo .sql
        $sql = File::get($rutaArchivo);

        // Ejecutar las consultas SQL
        DB::unprepared($sql);

        // Obtengo los registros de la tabla cat_distritos_ant
        $distritosAntiguos = DB::table('cat_distritos_ant')->get();

        // Itero los registros y los clono en la tabla distritos
        foreach ($distritosAntiguos as $distrito) {

            DB::table('distritos')->insert([
                'clave' => trim($distrito->clave),
                'nombre' => trim($distrito->distrito),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::statement('DROP TABLE IF EXISTS cat_distritos_ant;');
    }
}
