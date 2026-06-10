<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoreoMedioRadio extends Model
{
    use HasFactory;

    protected $table = 'monitoreo_medios_radio';

    protected $fillable = [
        'tipo_medio',
        'sujeto_id',
        'organizacion_politica_id',
        'periodo_id',
        'etapa_sujeto',
        'tipo_eleccion_id',
        'medio_nombre',
        'medio_siglas',
        'medio_banda',
        'medio_grupo_radiofonico',
        'medio_municipio_id',
        'medio_cobertura',
        'publicacion_fecha',
        'publicacion_hora',
        'publicacion_tiempo',
        'publicacion_tipo',
        'publicacion_ubicacion',
        'publicacion_modalidad',
        'genero_autor_id',
        'nombre_autor',
        'observaciones',
        'archivos',
        'cuali_valoracion',
        'cuali_lenguaje_inclusivo',
        'cuali_estereotipo',
        'cuali_violencia_temas_id',
        'cuali_tipos_eleccion_id',
        'cuali_resumen',
        'cuali_criterio_evaluacion',
    ];

    protected $casts = [
        'archivos' => 'array',
        'publicacion_fecha' => 'date',
        'publicacion_hora' => 'datetime:H:i',
    ];
}
