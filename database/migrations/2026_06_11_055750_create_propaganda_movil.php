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
        Schema::create('monitoreo_propaganda_movil', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_medio')->default('medios-propaganda-movil');
            // Campos comunes en todos los medios
            $table->foreignId('sujeto_id')->constrained('sujetos');
            $table->foreignId('organizacion_id')->nullable()->constrained('partidos')->comment('id de la organización política');
            $table->foreignId('periodo_id')->nullable()->constrained('periodos');
            $table->string('etapa_sujeto')->nullable();
            $table->foreignId('tipo_eleccion_id')->nullable()->constrained('tipos_eleccion');

            // Datos del medio
            $table->string('razon_social')->nullable()->comment('Razón social');
            $table->foreignId('distrito_id')->nullable()->constrained('distritos');
            $table->foreignId('municipio_id')->nullable()->constrained('municipios');
            $table->foreignId('localidad_id')->nullable()->constrained('localidades');
            $table->decimal('latitud', 18, 15)->nullable();
            $table->decimal('longitud', 18, 15)->nullable();
            $table->string('vialidad')->nullable();
            $table->string('seccion')->nullable();
            $table->string('unidad')->nullable()->comment('Unidad de transporte');
            $table->string('numero')->nullable()->comment('Número económico');
            $table->string('placa')->nullable()->comment('Número de placa del movil');

            // Datos de la publicación
            $table->string('publicacion_medidas')->nullable();
            $table->foreignId('publicacion_tipo_id')->nullable()->constrained('tipo_publicidad');
            $table->string('publicacion_version')->nullable();

            $table->string('referencia')->nullable();
            $table->string('referencia_domiciliaria')->nullable();

            $table->string('observaciones')->nullable();
            $table->json('archivos')->nullable();

            $table->foreignId('usuario1_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Usuario quien edite los datos cuantitativos');

            // Datos cualitativos
            $table->string('cuali_valoracion')->nullable()->comment('Select -> Positiva/Negativa/Neutral');
            $table->string('cuali_lenguaje_inclusivo')->nullable()->comment('Si,No');
            $table->string('cuali_estereotipo')->nullable()->comment('Select -> NA/Personas indígenas/Creencias religiosas de las personas/Personas afroamericanas/Personas de la diversidad sexual o de género/Personas jóvenes/Personas mayores/Personas con discapacidad/Personas que viven con VIH/Víctimas del delito');
            $table->foreignId('cuali_violencia_temas_id')->nullable()->constrained('violencia_temas')->comment('"Igualdad de género"; relación con violencia_temas');
            $table->string('cuali_objetividad')->nullable();
            $table->string('cuali_equidad')->nullable();
            $table->string('cuali_calidad')->nullable();

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
        Schema::dropIfExists('monitoreo_propaganda_movil');
    }
};
