<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoreoMedioTelevision extends Model
{
    use HasFactory;

    protected $table = 'monitoreo_television';

    protected $fillable = [
        'tipo_medio',
        'sujeto_id',
        'organizacion_id',
        'periodo_id',
        'etapa_sujeto',
        'tipo_eleccion_id',
        'medio_nombre',
        'medio_tipo_senal',
        'medio_municipio_id',
        'medio_plaza_id',
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
        'usuario1_id',
        'cuali_valoracion',
        'cuali_lenguaje_inclusivo',
        'cuali_estereotipo',
        'cuali_violencia_temas_id',
        'cuali_tipos_eleccion_id',
        'cuali_resumen',
        'cuali_criterio_evaluacion',
        'usuario2_id',
    ];

    protected $casts = [
        'archivos' => 'array',
        'publicacion_fecha' => 'date',
        'publicacion_hora' => 'datetime:H:i',
    ];

    public function capturista()
    {
        return $this->belongsTo(User::class, 'usuario1_id');
    }

    public function analista()
    {
        return $this->belongsTo(User::class, 'usuario2_id');
    }
}
