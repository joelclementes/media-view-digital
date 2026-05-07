<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function sujetos()
    {
        return $this->hasMany(Sujeto::class);
    }
}
