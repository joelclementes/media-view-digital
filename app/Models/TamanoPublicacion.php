<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamanoPublicacion extends Model
{
    use HasFactory;

    protected $table = 'tamanos_publicacion';
    protected $fillable = ['nombre'];
}
