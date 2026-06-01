<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoreoMedioElectronico extends Model
{
    use HasFactory;

    protected $table = 'monitoreo_medios_electronicos';

    protected $fillable = [
        'tipo_medio',
        'sujeto_id',
        'organizacion_politica_id',
        'periodo_id',
        'etapa_sujeto',
        'tipo_eleccion_id',
        'portal_internet_id',
        'url_pagina',
        'fecha',
        'tamano_id',
        'genero_id',
        'genero_sujeto_id',
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
        'cuali_modalidad',
        'cuali_objetividad',
        'cuali_tipo_mensaje',
        'cuali_formato',
    ];

    protected $casts = [
        'fecha' => 'date',
        'archivos' => 'array',
        'payload' => 'array',
    ];
}
