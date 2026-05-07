<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneroSujeto extends Model
{
    use HasFactory;

    protected $table = 'generos_sujetos';

    protected $fillable = ['nombre'];

    public function sujetos()
    {
        return $this->hasMany(Sujeto::class, 'genero_id');
    }
}
