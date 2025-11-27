<?php

namespace App\Models;

use App\Contracts\Locatable;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserShip extends Pivot implements Locatable
{
    protected $table = 'character_ships';
    
    protected $fillable = [
        'character_id',
        'ship_id',
        'name',
        'integrity',
        'solar_system_id',
        'location_type',
        'location_id',
        'coords_x',
        'coords_y',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function ship()
    {
        return $this->belongsTo(Ship::class);
    }

    public function solarSystem()
    {
        return $this->belongsTo(SolarSystem::class);
    }

    // Locatable Implementation
    public function getLocationType(): LocationType
    {
        return LocationType::SHIP;
    }

    public function parentLocation(): ?BelongsTo
    {
        // The ship is IN a system, or docked at a station
        if ($this->location_type === LocationType::STATION->value) {
            // Return station relationship? 
            // We don't have a direct relationship defined yet for polymorphic location
            return null; 
        }
        return $this->solarSystem();
    }

    public function getLocationPath(): array
    {
        return [$this->name ?? 'Nave'];
    }

    public function getCoordinates(): array
    {
        return [
            'x' => $this->coords_x,
            'y' => $this->coords_y,
            'z' => 0,
        ];
    }

    public function getSlug(): string
    {
        return $this->id; // Use ID for now
    }

    public function getDisplayName(): string
    {
        return $this->name ?? 'Nave';
    }
}
