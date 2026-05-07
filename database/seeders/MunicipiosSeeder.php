<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MunicipiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = database_path('seeders/cat_municipios_ant.sql');

        // Verificar si el archivo existe
        if (!File::exists($rutaArchivo)) {
            $this->command->error("El archivo $rutaArchivo no existe.");
            return;
        }

        // Leer el contenido del archivo .sql
        $sql = File::get($rutaArchivo);

        // Ejecutar las consultas SQL
        DB::unprepared($sql);

        // Obtengo los registros de la tabla cat_municipios_ant
        $municipiosAntiguos = DB::table('cat_municipios_ant')->get();

        // Itero los registros y los clono en la tabla municipios
        foreach ($municipiosAntiguos as $municipio) {

            DB::table('municipios')->insert([
                'nombre' => trim($municipio->nombre),
                'distrito_id' => trim($municipio->idDistrito),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::statement('DROP TABLE IF EXISTS cat_municipios_ant;');
    }
}
