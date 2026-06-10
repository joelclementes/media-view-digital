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
        Schema::create('monitoreo_medios_impresos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_medio')->default('medios-impresos');

            // Campos comunes en todos los medios
            $table->foreignId('sujeto_id')->constrained('sujetos');
            $table->foreignId('organizacion_politica_id')->nullable()->constrained('partidos');
            $table->foreignId('periodo_id')->nullable()->constrained('periodos');
            $table->string('etapa_sujeto')->nullable();
            $table->foreignId('tipo_eleccion_id')->nullable()->constrained('tipos_eleccion');
            
            $table->foreignId('medio_prensa_id')->nullable()->constrained('portales_prensa');
            
            $table->date('publicacion_fecha');
            $table->string('publicacion_lugar')->comment('Superior/Inferior/Centro/Derecha/Izquierda');
            $table->foreignId('publicacion_tamano_id')->constrained('tamanos_publicacion');
            $table->foreignId('publicacion_genero_id')->constrained('generos');
            $table->string('publicacion_seccion');
            $table->string('publicacion_pagina');

            $table->foreignId('genero_autor_id')->constrained('generos_sujetos');
            $table->string('nombre_autor')->nullable();

            $table->string('referencia')->nullable();

            $table->string('observaciones')->nullable();

            $table->json('archivos')->nullable();


            // valores directo en código (select).
            // Los valores descritos en ->comment()
            $table->string('cuali_valoracion')->nullable()->comment('Positiva/Negativa/Neutral');
            
            // valores directo en código (radiobutton)
            $table->string('cuali_lenguaje_inclusivo')->nullable()->comment('Si,No');
            
            // valores directo en código (select)
            // Los valores descritos en ->comment()
            $table->string('cuali_estereotipo')->nullable()->comment('NA/Personas indígenas/Creencias religiosas de las personas/Personas afroamericanas/Personas de la diversidad sexual o de género/Personas jóvenes/Personas mayores/Personas con discapacidad/Personas que viven con VIH/Víctimas del delito');
            
            // valores directo en código (select)
            $table->foreignId('cuali_violencia_temas_id')->nullable()->constrained('violencia_temas')->comment('"Igualdad de género"; relación con violencia_temas');
            
            // datos obtenidos de la tabla tipos_eleccion (select)
            $table->foreignId('cuali_tipos_eleccion_id')->nullable()->constrained('tipos_eleccion')->comment('"Candidatura"; relación con tipos_eleccion');
            
            // textarea
            $table->text('cuali_resumen')->nullable();
            
            // valores directo en código (select)
            // Los valores descritos en ->comment()
            $table->string('cuali_criterio_evaluacion')->nullable()->comment('Fotogafía B&N/Fotografía color/Caricatura/Emblema/Gráficos');
            
            // valores directo en código (select)
            // Los valores descritos en ->comment()
            $table->string('cuali_modalidad')->nullable()->comment('Politica/Electoral');
            
            //input
            $table->string('cuali_periodicidad')->nullable();
            
            // input numérico
            $table->integer('cuali_tiraje')->nullable();
            
            // valores directo en código (select)
            // Los valores descritos en ->comment()
            $table->string('cuali_circulacion')->nullable()->comment('Nacional/Regional/Local');
            
            // datos obtenidos de la tabla distritos (select)
            $table->foreignId('cuali_distritos_id')->nullable()->constrained('distritos')->comment('"Distrito"; relación con distritos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoreo_medios_impresos');
    }
};
