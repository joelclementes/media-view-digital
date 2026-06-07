<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoreoMedioImpreso extends Model
{
    use HasFactory;

    protected $table = 'monitoreo_medio_impresos';

    protected $fillable = [
        'tipo_medio',
        'sujeto_id',
        'organizacion_politica_id',
        'periodo_id',
        'etapa_sujeto',
        'tipo_eleccion_id',
        'medio_prensa_id',
        'publicacion_fecha',
        'publicacion_lugar',
        'publicacion_tamano_id',
        'publicacion_genero_id',
        'publicacion_seccion',
        'publicacion_pagina',
        'genero_autor_id',
        'nombre_autor',
        'referencia',
        'observaciones',
        'archivos',
        'cuali_valoracion',
        'cuali_lenguaje_inclusivo',
        'cuali_estereotipo',
        'cuali_violencia_temas_id',
        'cuali_tipos_eleccion_id',
        'cuali_resumen',
        'cuali_criterio_evaluacion',
        'cuali_modalidad',
        'cuali_periodicidad',
        'cuali_tiraje',
        'cuali_circulacion',
        'cuali_distritos_id',
    ];

    protected $casts = [
        'publicacion_fecha' => 'date',
        'archivos' => 'array',
    ];
}