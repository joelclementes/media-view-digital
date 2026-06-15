<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cine extends Model
{

    protected $table = 'cines';

    protected $fillable = [
    'nombre',
    'ubicacion',
    'url',
    'ciudad',
    'nombre_cine',
];
}
