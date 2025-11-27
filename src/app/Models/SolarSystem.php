<?php

namespace App\Models;

use App\Contracts\Locatable;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SolarSystem extends Model implements Locatable
{
    protected $fillable = ['name', 'slug', 'coords_x', 'coords_y'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

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

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    // Locatable Implementation
    public function getLocationType(): LocationType
    {
        return LocationType::SYSTEM;
    }

    public function parentLocation(): ?BelongsTo
    {
        return null; // Systems are top-level for now
    }

    public function getLocationPath(): array
    {
        return [$this->name];
    }

    public function getCoordinates(): array
    {
        return [
            'x' => $this->x_coordinate,
            'y' => $this->y_coordinate,
            'z' => $this->z_coordinate,
        ];
    }

    public function getSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getDisplayName(): string
    {
        return $this->name;
    }
}
