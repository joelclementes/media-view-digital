<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolenciaTema extends Model
{
    use HasFactory;

    protected $table = 'violencia_temas';

    protected $fillable = [
        'nombre',
    ];
}