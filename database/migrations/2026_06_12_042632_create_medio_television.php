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
        Schema::create('monitoreo_television', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_medio')->default('medios-soportes-promocionales');
            // Campos comunes en todos los medios
            $table->foreignId('sujeto_id')->constrained('sujetos');
            $table->foreignId('organizacion_id')->nullable()->constrained('partidos')->comment('id de la organización política');
            $table->foreignId('periodo_id')->nullable()->constrained('periodos');
            $table->string('etapa_sujeto')->nullable();
            $table->foreignId('tipo_eleccion_id')->nullable()->constrained('tipos_eleccion');

            $table->string('medio_nombre')->nullable();
            $table->string('medio_tipo_senal')->nullable()->comment('Select -> Abierta/Restringida/Tv por cable');
            $table->foreignId('medio_municipio_id')->nullable()->constrained('municipios');
            $table->foreignId('medio_plaza_id')->nullable()->constrained('municipios');
            $table->string('medio_cobertura')->nullable()->comment('Select -> Nacional/Regional/Local');

            $table->date('publicacion_fecha')->nullable();
            $table->time('publicacion_hora')->nullable();
            $table->integer('publicacion_tiempo')->nullable()->comment('Tiempo en segundos');
            $table->string('publicacion_tipo')->nullable()->comment('Select -> Nota informativa/Nota periodística/Entrevista/Reportaje');
            $table->string('publicacion_ubicacion')->nullable()->comment('Select -> Al inicio/En el desarrollo/Al final');
            $table->string('publicacion_modalidad')->nullable()->comment('Select -> Política/Electoral');

            $table->foreignId('genero_autor_id')->constrained('generos_sujetos');
            $table->string('nombre_autor')->nullable();

            $table->string('observaciones')->nullable();

            $table->json('archivos')->nullable();


            // Los valores descritos en ->comment()
            $table->string('cuali_valoracion')->nullable()->comment('Select -> Positiva/Negativa/Neutral');

            // valores directo en código (radiobutton)
            $table->string('cuali_lenguaje_inclusivo')->nullable()->comment('Radiobuttons -> Si,No');

            // Los valores descritos en ->comment()
            $table->string('cuali_estereotipo')->nullable()->comment('Select -> NA/Personas indígenas/Creencias religiosas de las personas/Personas afroamericanas/Personas de la diversidad sexual o de género/Personas jóvenes/Personas mayores/Personas con discapacidad/Personas que viven con VIH/Víctimas del delito');

            // valores directo en código (select)
            $table->foreignId('cuali_violencia_temas_id')->nullable()->constrained('violencia_temas')->comment('"Igualdad de género"; relación con violencia_temas');

            // datos obtenidos de la tabla tipos_eleccion (select)
            $table->foreignId('cuali_tipos_eleccion_id')->nullable()->constrained('tipos_eleccion')->comment('"Candidatura"; relación con tipos_eleccion');

            // textarea
            $table->text('cuali_resumen')->nullable();

            // Los valores descritos en ->comment()
            $table->string('cuali_criterio_evaluacion')->nullable()->comment('Select -> Presentación directa/Cita y voz/Cita y audio/Solo cita/Voz de las y los ciudadanos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoreo_television');
    }
};
