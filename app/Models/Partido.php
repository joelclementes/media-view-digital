<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;

    protected $table = 'partidos';

    protected $fillable = [
        'nombre',
        'siglas',
        'logo',
        'tipo'
    ];

    public function sujetos()
    {
        return $this->hasMany(Sujeto::class);
    }
}
