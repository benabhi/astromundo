<?php

namespace App\Contracts;

/**
 * Interface for entities that can be navigated to
 */
interface Navigable
{
    /**
     * Check if the player can navigate to this location
     */
    public function canNavigateTo(mixed $player): bool;

    /**
     * Get the distance from a given location
     */
    public function getDistanceFrom(Locatable $from): float;

    /**
     * Get the travel time from a given location (in seconds)
     */
    public function getTravelTimeFrom(Locatable $from, ?float $speed = null): int;

    /**
     * Get navigation requirements (fuel, permissions, etc.)
     */
    public function getNavigationRequirements(): array;

    /**
     * Check if this location is within scanner range from another location
     */
    public function isInScannerRange(Locatable $from, float $scannerRange): bool;
}
