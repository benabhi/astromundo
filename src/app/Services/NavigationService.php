<?php

namespace App\Services;

use App\Contracts\Locatable;
use App\Contracts\Navigable;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Service for navigation calculations and route planning
 */
class NavigationService
{
    private const BASE_SPEED = 100; // Base speed units per second
    private const FUEL_CONSUMPTION_RATE = 0.1; // Fuel per distance unit

    /**
     * Calculate distance between two locations
     */
    public function calculateDistance(Locatable $from, Locatable $to): float
    {
        $fromCoords = $from->getCoordinates();
        $toCoords = $to->getCoordinates();

        // 3D Euclidean distance
        $dx = $toCoords['x'] - $fromCoords['x'];
        $dy = $toCoords['y'] - $fromCoords['y'];
        $dz = $toCoords['z'] - $fromCoords['z'];

        return sqrt($dx ** 2 + $dy ** 2 + $dz ** 2);
    }

    /**
     * Calculate travel time between two locations
     */
    public function calculateTravelTime(Locatable $from, Locatable $to, ?float $speed = null): int
    {
        $distance = $this->calculateDistance($from, $to);
        $effectiveSpeed = $speed ?? self::BASE_SPEED;

        return (int) ceil($distance / $effectiveSpeed);
    }

    /**
     * Calculate fuel required for travel
     */
    public function calculateFuelRequired(Locatable $from, Locatable $to): float
    {
        $distance = $this->calculateDistance($from, $to);
        return $distance * self::FUEL_CONSUMPTION_RATE;
    }

    /**
     * Check if player can navigate to a location
     */
    public function canNavigate(User $player, Navigable $destination): bool
    {
        // Check if destination allows navigation
        if (!$destination->canNavigateTo($player)) {
            return false;
        }

        // Check if player has enough fuel
        $currentLocation = app(LocationService::class)->getCurrentLocation($player);

        if (!$currentLocation) {
            return false;
        }

        $fuelRequired = $this->calculateFuelRequired($currentLocation, $destination);

        if ($player->ship && $player->ship->fuel < $fuelRequired) {
            return false;
        }

        return true;
    }

    /**
     * Get navigation requirements for a destination
     */
    public function getNavigationRequirements(Locatable $from, Navigable $destination): array
    {
        $distance = $this->calculateDistance($from, $destination);
        $fuelRequired = $this->calculateFuelRequired($from, $destination);
        $travelTime = $this->calculateTravelTime($from, $destination);

        return [
            'distance' => round($distance, 2),
            'fuel_required' => round($fuelRequired, 2),
            'travel_time' => $travelTime,
            'travel_time_formatted' => $this->formatTravelTime($travelTime),
            'additional_requirements' => $destination->getNavigationRequirements(),
        ];
    }

    /**
     * Check if location is in scanner range
     */
    public function isInScannerRange(Locatable $from, Locatable $to, float $scannerRange): bool
    {
        $distance = $this->calculateDistance($from, $to);
        return $distance <= $scannerRange;
    }

    /**
     * Get all locations within scanner range
     */
    public function getLocationsInRange(Locatable $from, float $scannerRange, array $locations): array
    {
        return array_filter($locations, function ($location) use ($from, $scannerRange) {
            return $this->isInScannerRange($from, $location, $scannerRange);
        });
    }

    /**
     * Format travel time for display
     */
    private function formatTravelTime(int $seconds): string
    {
        if ($seconds < 60) {
            return "{$seconds}s";
        }

        if ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            return "{$minutes}m {$remainingSeconds}s";
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return "{$hours}h {$minutes}m";
    }

    /**
     * Log navigation event
     */
    public function logNavigation(User $player, Locatable $from, Locatable $to): void
    {
        Log::info('Player navigation', [
            'player_id' => $player->id,
            'from' => [
                'type' => $from->getLocationType()->value,
                'name' => $from->getDisplayName(),
            ],
            'to' => [
                'type' => $to->getLocationType()->value,
                'name' => $to->getDisplayName(),
            ],
            'distance' => $this->calculateDistance($from, $to),
        ]);
    }
}
