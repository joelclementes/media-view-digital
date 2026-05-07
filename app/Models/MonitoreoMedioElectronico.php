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
        'payload',
    ];

    protected $casts = [
        'fecha' => 'date',
        'archivos' => 'array',
        'payload' => 'array',
    ];
}
