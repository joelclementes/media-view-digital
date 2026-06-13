<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoportePromocional extends Model
{
    use HasFactory;

    protected $table = 'monit_soportes_promocionales';

    protected $fillable = [
        'tipo_medio',
        'sujeto_id',
        'organizacion_id',
        'periodo_id',
        'etapa_sujeto',
        'tipo_eleccion_id',
        'distrito_id',
        'municipio_id',
        'localidad_id',
        'latitud',
        'longitud',
        'vialidad',
        'seccion',
        'publicacion_medidas',
        'publicacion_tipo_id',
        'publicacion_version',
        'publicacion_cantidad',
        'publicacion_numero_fotos',
        'referencia',
        'referencia_domiciliaria',
        'observaciones',
        'archivos',
        'cuali_valoracion',
        'cuali_lenguaje_inclusivo',
        'cuali_estereotipo',
        'cuali_violencia_temas_id',
        'cuali_objetividad',
        'cuali_equidad',
        'cuali_calidad',
    ];

    protected $casts = [
        'archivos' => 'array',
        'latitud' => 'decimal:15',
        'longitud' => 'decimal:15',
        'publicacion_cantidad' => 'integer',
        'publicacion_numero_fotos' => 'integer',
    ];
}
