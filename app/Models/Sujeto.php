<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sujeto extends Model
{
    use HasFactory;

    protected $table = 'sujetos';

    protected $fillable = [
        'nombre',
        'genero_id',        // Cambiado de 'genero' a 'genero_id'
        'municipio_id',
        'partido_id',
    ];

    // Relación con género
    public function genero()
    {
        return $this->belongsTo(GeneroSujeto::class, 'genero_id');
    }

    // Relación con municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    // Relación con partido
    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }
}
