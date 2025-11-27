<?php

namespace App\Models;

use App\Contracts\Locatable;
use App\Contracts\Navigable;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Moon extends Model implements Locatable, Navigable
{
    protected $guarded = [];

    protected $fillable = ['planet_id', 'name', 'slug', 'type', 'attributes'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

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

    // Locatable Implementation
    public function getLocationType(): LocationType
    {
        return LocationType::MOON;
    }

    public function parentLocation(): ?BelongsTo
    {
        return $this->planet();
    }

    public function getLocationPath(): array
    {
        return [
            $this->planet->solarSystem->name ?? 'Unknown System',
            $this->planet->name ?? 'Unknown Planet',
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
        return true;
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
            'type' => 'moon',
            'atmosphere' => $this->attributes['atmosphere'] ?? 'none',
        ];
    }

    public function isInScannerRange(Locatable $from, float $scannerRange): bool
    {
        return $this->getDistanceFrom($from) <= $scannerRange;
    }
}
