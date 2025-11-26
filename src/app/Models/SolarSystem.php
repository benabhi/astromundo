<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarSystem extends Model
{
    protected $guarded = [];

    public function stars()
    {
        return $this->hasMany(Star::class);
    }

    public function planets()
    {
        return $this->hasMany(Planet::class);
    }

    public function stargates()
    {
        return $this->hasMany(Stargate::class);
    }
}
