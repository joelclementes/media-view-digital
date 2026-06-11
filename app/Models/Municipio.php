<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'distrito_id',
    ];

    public function distrito()
    {
        return $this->belongsTo(Distrito::class);
    }

    public function localidades()
    {
        return $this->hasMany(Localidad::class);
    }

    public function sujetos()
    {
        return $this->hasMany(Sujeto::class);
    }
}