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
        Schema::create('monitoreo_cine', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_medio')->default('medios-cine');
            // Campos comunes en todos los medios
            $table->foreignId('sujeto_id')->constrained('sujetos');
            $table->foreignId('organizacion_id')->nullable()->constrained('partidos')->comment('id de la organización política');
            $table->foreignId('periodo_id')->nullable()->constrained('periodos');
            $table->string('etapa_sujeto')->nullable();
            $table->foreignId('tipo_eleccion_id')->nullable()->constrained('tipos_eleccion');

            $table->foreignId('medio_cine_id')->nullable()->constrained('cines');
            $table->foreignId('medio_distrito_id')->nullable()->constrained('distritos');
            $table->foreignId('medio_municipio_id')->nullable()->constrained('municipios');
            $table->foreignId('medio_localidad_id')->nullable()->constrained('localidades');
            $table->string('medio_sala')->nullable();

            $table->date('publicacion_fecha')->nullable();
            $table->time('publicacion_hora')->nullable();
            $table->integer('publicacion_tiempo')->nullable()->comment('Duración de la publicación. Tiempo en segundos');

            $table->string('referencia')->nullable();
            $table->text('observaciones')->nullable();
            $table->json('archivos')->nullable();

            $table->foreignId('usuario1_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Usuario quien edite los datos cuantitativos');

            // Los valores descritos en ->comment()
            $table->string('cuali_valoracion')->nullable()->comment('Select -> Positiva/Negativa/Neutral');

            // valores directo en código (radiobutton)
            $table->string('cuali_lenguaje_inclusivo')->nullable()->comment('Radiobuttons -> Si,No');

            // Los valores descritos en ->comment()
            $table->string('cuali_estereotipo')->nullable()->comment('Select -> NA/Personas indígenas/Creencias religiosas de las personas/Personas afroamericanas/Personas de la diversidad sexual o de género/Personas jóvenes/Personas mayores/Personas con discapacidad/Personas que viven con VIH/Víctimas del delito');

            // valores directo en código (select)
            $table->foreignId('cuali_violencia_temas_id')->nullable()->constrained('violencia_temas')->comment('"Igualdad de género"; relación con violencia_temas');

            // textarea
            $table->text('cuali_resumen')->nullable();

            // Los valores descritos en ->comment()
            $table->string('cuali_criterio_evaluacion')->nullable()->comment('Select -> Presentación directa/Cita y voz/Cita y audio/Solo cita/Voz de las y los ciudadanos');

            $table->foreignId('usuario2_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Usuario quien edite los datos cuantitativos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoreo_cine');
    }
};
