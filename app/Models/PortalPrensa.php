<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalPrensa extends Model
{
    protected $table = 'portales_prensa';

    protected $fillable = [
        'nombre',
        'ciudad',
        'tipo',
    ];
}