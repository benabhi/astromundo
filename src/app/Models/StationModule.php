<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationModule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
