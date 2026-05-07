<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LocalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = database_path('seeders/cat_localidades_ant.sql');

        // Verificar si el archivo existe
        if (!File::exists($rutaArchivo)) {
            $this->command->error("El archivo $rutaArchivo no existe.");
            return;
        }

        // Leer el contenido del archivo .sql
        $sql = File::get($rutaArchivo);

        // Ejecutar las consultas SQL
        DB::unprepared($sql);

        // Obtengo los registros de la tabla cat_localidades_ant
        $localidadesAntiguas = DB::table('cat_localidades_ant')->get();

        // Itero los registros y los clono en la tabla localidades
        foreach ($localidadesAntiguas as $localidad) {

            DB::table('localidades')->insert([
                'nombre' => trim($localidad->localidad),
                'tipo' => trim($localidad->tipo),
                'municipio_id' => trim($localidad->idMunicipio),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::statement('DROP TABLE IF EXISTS cat_localidades_ant;');
    }
}
