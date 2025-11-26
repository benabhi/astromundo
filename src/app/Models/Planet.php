<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function solarSystem()
    {
        return $this->belongsTo(SolarSystem::class);
    }

    public function moons()
    {
        return $this->hasMany(Moon::class);
    }
}
