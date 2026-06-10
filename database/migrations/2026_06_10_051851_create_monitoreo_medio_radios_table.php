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
        Schema::create('monitoreo_medios_radio', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_medio')->default('medios-radio');
            // Campos comunes en todos los medios
            $table->foreignId('sujeto_id')->constrained('sujetos');
            $table->foreignId('organizacion_politica_id')->nullable()->constrained('partidos');
            $table->foreignId('periodo_id')->nullable()->constrained('periodos');
            $table->string('etapa_sujeto')->nullable();
            $table->foreignId('tipo_eleccion_id')->nullable()->constrained('tipos_eleccion');

            $table->string('medio_nombre')->nullable();
            $table->string('medio_siglas')->nullable();
            $table->string('medio_banda')->nullable();
            $table->string('medio_grupo_radiofonico')->nullable();
            $table->foreignId('medio_municipio_id')->nullable()->constrained('municipios');
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


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoreo_medios_radio');
    }
};
