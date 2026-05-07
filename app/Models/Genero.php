<?php
// app/Models/Genero.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'generos';

    protected $fillable = [
        'nombre',
        'medio'
    ];

    /**
     * Scope para filtrar por medio
     */
    public function scopePorMedio($query, $medio)
    {
        return $query->where('medio', $medio);
    }
}
