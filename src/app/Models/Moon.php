<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
