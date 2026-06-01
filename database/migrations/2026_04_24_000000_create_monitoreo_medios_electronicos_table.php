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
            $table->foreignId('genero_sujeto_id')->constrained('generos_sujetos');
            $table->string('nombre_autor')->nullable();
            $table->string('referencia')->nullable();
            $table->string('observaciones')->nullable();
            $table->json('archivos')->nullable();

            // valores directo en código
            $table->string('cuali_valoracion')->comment('Positiva/Negativa/Neutral');

            // valores directo en código
            $table->string('cuali_lenguaje_inclusivo')->comment('Si,No');

            // valores directo en código
            $table->string('cuali_estereotipo')->comment('NA/Personas indígenas/Creencias religiosas de las personas/Personas afroamericanas... ');

            // valores directo en código
            $table->foreignId('cuali_violencia_temas_id')->constrained('violencia_temas')->comment('"Igualdad de género"; relación con violencia_temas');

            // datos obtenidos de la tabla tipos_eleccion
            $table->foreignId('cuali_tipos_eleccion_id')->constrained('tipos_eleccion')->comment('"Candidatura"; relación con tipos_eleccion');

            $table->text('cuali_resumen');

            // valores directo en código
            $table->string('cuali_modalidad')->comment('Politica/Electoral');

            $table->text('cuali_objetividad');

            // valores directo en código
            $table->string('cuali_tipo_mensaje')->comment('A favor/Descalificativo/Crítica/Imparcial');
            $table->string('cuali_formato')->comment('Mensaje/De entrevista/Informativo-narrativo');
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
