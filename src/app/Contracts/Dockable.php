<?php

namespace App\Contracts;

/**
 * Interface for entities where ships can dock
 */
interface Dockable
{
    /**
     * Check if the player can dock at this location
     */
    public function canDock(mixed $player): bool;

    /**
     * Get the docking fee (if any)
     */
    public function getDockingFee(): int;

    /**
     * Get available docking bays
     */
    public function getAvailableDockingBays(): int;

    /**
     * Get total docking capacity
     */
    public function getTotalDockingCapacity(): int;

    /**
     * Check if docking bays are available
     */
    public function hasDockingBaysAvailable(): bool;

    /**
     * Dock a ship
     */
    public function dock(mixed $ship): bool;

    /**
     * Undock a ship
     */
    public function undock(mixed $ship): bool;

    /**
     * Get list of currently docked ships
     */
    public function getDockedShips(): array;
}
