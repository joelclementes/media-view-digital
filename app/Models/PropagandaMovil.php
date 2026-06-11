<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropagandaMovil extends Model
{
    use HasFactory;

    protected $table = 'propaganda_movil';

    protected $fillable = [
        'tipo_medio',
        'sujeto_id',
        'organizacion_id',
        'periodo_id',
        'etapa_sujeto',
        'tipo_eleccion_id',
        'razon_social',
        'distrito_id',
        'municipio_id',
        'localidad_id',
        'latitud',
        'longitud',
        'vialidad',
        'seccion',
        'unidad',
        'numero',
        'placa',
        'publicacion_medidas',
        'publicacion_tipo_id',
        'publicacion_version',
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
    ];
}
