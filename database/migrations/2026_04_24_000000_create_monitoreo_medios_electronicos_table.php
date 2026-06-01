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
