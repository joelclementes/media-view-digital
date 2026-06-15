<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoreoMedioCine extends Model
{
    use HasFactory;

    protected $table = 'monitoreo_cine';

    protected $fillable = [
        'tipo_medio',
        'sujeto_id',
        'organizacion_id',
        'periodo_id',
        'etapa_sujeto',
        'tipo_eleccion_id',
        'medio_cine_id',
        'medio_distrito_id',
        'medio_municipio_id',
        'medio_localidad_id',
        'medio_sala',
        'publicacion_fecha',
        'publicacion_hora',
        'publicacion_tiempo',
        'referencia',
        'observaciones',
        'archivos',
        'cuali_valoracion',
        'cuali_lenguaje_inclusivo',
        'cuali_estereotipo',
        'cuali_violencia_temas_id',
        'cuali_resumen',
        'cuali_criterio_evaluacion',
    ];

    protected $casts = [
        'archivos' => 'array',
        'publicacion_fecha' => 'date',
        'publicacion_hora' => 'datetime:H:i',
    ];
}
