<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function solarSystem()
    {
        return $this->belongsTo(SolarSystem::class);
    }
}
