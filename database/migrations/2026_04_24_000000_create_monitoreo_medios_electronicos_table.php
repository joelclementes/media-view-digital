<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitoreo_medios_electronicos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_medio')->default('medios-electronicos');

            // Campos comunes en todos los medios
            $table->foreignId('sujeto_id')->constrained('sujetos');
            $table->foreignId('organizacion_politica_id')->nullable()->constrained('partidos');
            $table->foreignId('periodo_id')->nullable()->constrained('periodos');
            $table->string('etapa_sujeto')->nullable();
            $table->foreignId('tipo_eleccion_id')->nullable()->constrained('tipos_eleccion');

            $table->foreignId('portal_internet_id')->nullable()->constrained('portales_internet');
            $table->text('url_pagina');

            $table->date('fecha');
            $table->foreignId('tamano_id')->constrained('tamanos_publicacion');
            $table->foreignId('genero_id')->constrained('generos');

            $table->foreignId('genero_autor_id')->constrained('generos_sujetos');
            $table->string('nombre_autor')->nullable();

            $table->string('referencia')->nullable();

            $table->string('observaciones')->nullable();

            $table->json('archivos')->nullable();

            $table->boolean('validado')->default(false);

            $table->foreignId('usuario1_id')->nullable()->constrained('users')->comment('Usuario quien edite los datos cuantitativos');

            // valores directo en código (select)
            $table->string('cuali_valoracion')->nullable()->comment('Positiva/Negativa/Neutral');

            // valores directo en código (radiobutton)
            $table->string('cuali_lenguaje_inclusivo')->nullable()->comment('Si,No');

            // valores directo en código (select)
            $table->string('cuali_estereotipo')->nullable()->comment('NA/Personas indígenas/Creencias religiosas de las personas/Personas afroamericanas/Personas de la diversidad sexual o de género/Personas jóvenes/Personas mayores/Personas con discapacidad/Personas que viven con VIH/Víctimas del delito');

            // valores directo en código (select)
            $table->foreignId('cuali_violencia_temas_id')->nullable()->constrained('violencia_temas')->comment('"Igualdad de género"; relación con violencia_temas');

            // datos obtenidos de la tabla tipos_eleccion (select)
            $table->foreignId('cuali_tipos_eleccion_id')->nullable()->constrained('tipos_eleccion')->comment('"Candidatura"; relación con tipos_eleccion');

            // textarea
            $table->text('cuali_resumen')->nullable();

            // valores directo en código (select)
            $table->string('cuali_modalidad')->nullable()->comment('Politica/Electoral');

            //textarea
            $table->text('cuali_objetividad')->nullable();

            // valores directo en código (select)
            $table->string('cuali_tipo_mensaje')->nullable()->comment('A favor/Descalificativo/Crítica/Imparcial');
            // valores directo en código (select)
            $table->string('cuali_formato')->nullable()->comment('Mensaje/De entrevista/Informativo-narrativo');

            $table->foreignId('usuario2_id')->nullable()->constrained('users')->comment('Usuario quien edite los datos cualitativos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoreo_medios_electronicos');
    }
};
