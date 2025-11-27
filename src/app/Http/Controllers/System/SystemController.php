<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\LocationController;
use App\Models\SolarSystem;
use Illuminate\Http\Request;

/**
 * Controller for solar system views and navigation
 */
class SystemController extends LocationController
{
    /**
     * Display the solar system overview
     */
    public function show(Request $request, SolarSystem $system)
    {
        // Update player location to this system
        $this->updatePlayerLocation($request, $system);

        // Get all entities in this system
        $planets = $system->planets()->with('moons')->get();
        $stations = $system->stations()->get();
        $stargates = $system->stargates()->get();

        return $this->viewWithLocation('system.show', [
            'system' => $system,
            'planets' => $planets,
            'stations' => $stations,
            'stargates' => $stargates,
        ]);
    }

    /**
     * Display the system map for navigation
     */
    public function map(Request $request, SolarSystem $system)
    {
        $player = $this->getPlayer($request);
        $currentLocation = $this->getCurrentLocation($request);

        // Get all navigable entities in the system
        $entities = collect()
            ->merge($system->planets)
            ->merge($system->stations)
            ->merge($system->stargates);

        // Calculate navigation data for each entity
        $entitiesWithNav = $entities->map(function ($entity) use ($currentLocation) {
            return [
                'entity' => $entity,
                'navigation' => $this->navigationService->getNavigationRequirements(
                    $currentLocation,
                    $entity
                ),
            ];
        });

        return $this->viewWithLocation('system.map', [
            'system' => $system,
            'entities' => $entitiesWithNav,
            'playerShip' => $player->ship,
        ]);
    }
}
