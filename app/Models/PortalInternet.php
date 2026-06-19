<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PortalInternet extends Model
{
    protected $table = 'portales_internet';

    protected $fillable = [
        'nombre',
        'url',
        'ciudad',
        'tipo',
    ];

    public function capturistas(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'capturista_portal_internet',
            'portal_internet_id',
            'user_id'
        )->withTimestamps();
    }
}