<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = 'localidades';

    protected $fillable = [
        'nombre',
        'tipo',
        'municipio_id',
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }
}