<?php

namespace App\Models;

use App\Contracts\Dockable;
use App\Contracts\Locatable;
use App\Contracts\Navigable;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Station extends Model implements Locatable, Navigable, Dockable
{
    protected $fillable = ['moon_id', 'solar_system_id', 'name', 'slug', 'type', 'description', 'attributes'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    protected $casts = [
        'attributes' => 'array',
    ];

    public function moon()
    {
        return $this->belongsTo(Moon::class);
    }

    public function solarSystem()
    {
        return $this->belongsTo(SolarSystem::class);
    }

    public function modules()
    {
        return $this->hasMany(StationModule::class);
    }

    public function marketItems()
    {
        return $this->hasMany(MarketItem::class); // Assuming this model exists or will exist
    }

    public function availableShips()
    {
        return $this->hasMany(Ship::class); // Placeholder
    }

    // Locatable Implementation
    public function getLocationType(): LocationType
    {
        return LocationType::STATION;
    }

    public function parentLocation(): ?BelongsTo
    {
        return $this->solarSystem(); // Or moon() depending on logic
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
            'type' => 'station',
            'docking_fee' => $this->getDockingFee(),
        ];
    }

    public function isInScannerRange(Locatable $from, float $scannerRange): bool
    {
        return $this->getDistanceFrom($from) <= $scannerRange;
    }

    // Dockable Implementation
    public function canDock(mixed $player): bool
    {
        return true; // Add logic for reputation/permissions
    }

    public function getDockingFee(): int
    {
        return 100; // Default fee, could be a column
    }

    public function getAvailableDockingBays(): int
    {
        return 5; // Placeholder
    }

    public function getTotalDockingCapacity(): int
    {
        return 10; // Placeholder
    }

    public function hasDockingBaysAvailable(): bool
    {
        return $this->getAvailableDockingBays() > 0;
    }

    public function dock(mixed $ship): bool
    {
        // Logic to dock ship
        return true;
    }

    public function undock(mixed $ship): bool
    {
        // Logic to undock ship
        return true;
    }

    public function getDockedShips(): array
    {
        return []; // Placeholder
    }

    public function isShipDocked(mixed $ship): bool
    {
        // Check if ship is docked here
        return false; // Placeholder
    }
}
