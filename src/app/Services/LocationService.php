<?php

namespace App\Services;

use App\Contracts\Locatable;
use App\Enums\LocationType;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service for managing player location and movement
 */
class LocationService
{
    private const CACHE_PREFIX = 'player_location:';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get the current location of a player
     */
    public function getCurrentLocation(User $player): ?Locatable
    {
        // Try cache first
        $cacheKey = self::CACHE_PREFIX . $player->id;
        $cached = Cache::get($cacheKey);

        if ($cached) {
            return $this->resolveLocation($cached['type'], $cached['id']);
        }

        // Fallback to database
        if ($player->current_location_type && $player->current_location_id) {
            $location = $this->resolveLocation(
                LocationType::from($player->current_location_type),
                $player->current_location_id
            );

            if ($location) {
                $this->cacheLocation($player, $location);
            }

            return $location;
        }

        return null;
    }

    /**
     * Update player's current location
     */
    public function updateLocation(User $player, Locatable $location): bool
    {
        $locationType = $location->getLocationType();

        $player->update([
            'current_location_type' => $locationType->value,
            'current_location_id' => $location->id,
        ]);

        $this->cacheLocation($player, $location);

        Log::info('Player location updated', [
            'player_id' => $player->id,
            'location_type' => $locationType->value,
            'location_id' => $location->id,
            'location_name' => $location->getDisplayName(),
        ]);

        return true;
    }

    /**
     * Check if player can access a location
     */
    public function canAccessLocation(User $player, Locatable $location): bool
    {
        // Check if player has required permissions
        // This can be extended with more complex logic

        return true; // Basic implementation
    }

    /**
     * Get location breadcrumb for UI
     */
    public function getLocationBreadcrumb(Locatable $location): array
    {
        return $location->getLocationPath();
    }

    /**
     * Resolve a location from type and ID
     */
    private function resolveLocation(LocationType $type, int $id): ?Locatable
    {
        $model = $this->getModelForLocationType($type);

        if (!$model) {
            return null;
        }

        return $model::find($id);
    }

    /**
     * Get the model class for a location type
     */
    private function getModelForLocationType(LocationType $type): ?string
    {
        return match($type) {
            LocationType::SYSTEM => \App\Models\SolarSystem::class,
            LocationType::STATION => \App\Models\Station::class,
            LocationType::PLANET => \App\Models\Planet::class,
            LocationType::MOON => \App\Models\Moon::class,
            LocationType::SHIP => \App\Models\Ship::class,
            default => null,
        };
    }

    /**
     * Cache player location
     */
    private function cacheLocation(User $player, Locatable $location): void
    {
        $cacheKey = self::CACHE_PREFIX . $player->id;

        Cache::put($cacheKey, [
            'type' => $location->getLocationType(),
            'id' => $location->id,
        ], self::CACHE_TTL);
    }

    /**
     * Clear player location cache
     */
    public function clearLocationCache(User $player): void
    {
        $cacheKey = self::CACHE_PREFIX . $player->id;
        Cache::forget($cacheKey);
    }
}
