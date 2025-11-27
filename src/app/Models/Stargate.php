<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Locatable;
use App\Contracts\Navigable;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Stargate extends Model implements Locatable, Navigable
{
    protected $guarded = [];

    public function solarSystem()
    {
        return $this->belongsTo(SolarSystem::class);
    }

    public function destinationSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'destination_system_id');
    }

    // Locatable Implementation
    public function getLocationType(): LocationType
    {
        return LocationType::STARGATE;
    }

    public function parentLocation(): ?BelongsTo
    {
        return $this->solarSystem();
    }

    public function getLocationPath(): array
    {
        return [
            $this->solarSystem->name ?? 'Unknown System',
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
            'type' => 'stargate',
            'destination' => $this->destinationSystem->name ?? 'Unknown',
        ];
    }

    public function isInScannerRange(Locatable $from, float $scannerRange): bool
    {
        return $this->getDistanceFrom($from) <= $scannerRange;
    }
}
