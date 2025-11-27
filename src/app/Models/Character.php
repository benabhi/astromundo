<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Skill;
use App\Models\Ship;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'age',
        'date_of_birth',
        'location_id', // Legacy? Maybe remove later
        'station_id',
        'station_module_id',
        'happiness',
        'integrity',
        'energy',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function currentModule()
    {
        return $this->belongsTo(StationModule::class, 'station_module_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'character_skills')
            ->withPivot('level', 'xp')
            ->withTimestamps();
    }

    public function ships()
    {
        return $this->belongsToMany(Ship::class, 'character_ships')
            ->using(UserShip::class)
            ->withPivot('id', 'name', 'integrity', 'solar_system_id', 'location_type', 'location_id')
            ->withTimestamps();
    }
}
