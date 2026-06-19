<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapturistaPortalInternet extends Model
{
    protected $table = 'capturista_portal_internet';

    protected $fillable = [
        'user_id',
        'portal_internet_id',
    ];

    public function capturista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function portal(): BelongsTo
    {
        return $this->belongsTo(PortalInternet::class, 'portal_internet_id');
    }
}