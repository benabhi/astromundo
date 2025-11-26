<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function moon()
    {
        return $this->belongsTo(Moon::class);
    }

    public function modules()
    {
        return $this->hasMany(StationModule::class);
    }
}
