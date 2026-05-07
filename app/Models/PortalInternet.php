<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalInternet extends Model
{
    use HasFactory;

    protected $table = 'portales_internet';

    protected $fillable = [
        'nombre',
        'url',
        'ciudad',
        'tipo'
    ];
}
