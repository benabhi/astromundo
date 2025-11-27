<?php

namespace App\Models;

use App\Contracts\Locatable;
use App\Contracts\Navigable;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Planet extends Model implements Locatable, Navigable
{
    protected $fillable = ['solar_system_id', 'name', 'slug', 'type', 'orbit_index', 'attributes'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

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

    // Locatable Implementation
    public function getLocationType(): LocationType
    {
        return LocationType::PLANET;
    }

    public function parentLocation(): ?BelongsTo
    {
        return $this->solarSystem();
    }

    public function getLocationPath(): array
    {
        return [
            $this->solarSystem->name,
            $this->name
        ];
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

    // Navigable Implementation
    public function canNavigateTo(mixed $player): bool
    {
        return true; // Planets are generally navigable
    }

    public function getDistanceFrom(Locatable $from): float
    {
        return app(\App\Services\NavigationService::class)->calculateDistance($from, $this);
    }

    public function getTravelTimeFrom(Locatable $from, ?float $speed = null): int
    {
        return app(\App\Services\NavigationService::class)->calculateTravelTime($from, $this, $speed);
    }

    public function getNavigationRequirements(): array
    {
        return [
            'type' => 'planet',
            'atmosphere' => $this->attributes['atmosphere'] ?? 'unknown',
        ];
    }

    public function isInScannerRange(Locatable $from, float $scannerRange): bool
    {
        return $this->getDistanceFrom($from) <= $scannerRange;
    }
}
