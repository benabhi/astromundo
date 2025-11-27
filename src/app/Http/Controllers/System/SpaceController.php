<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\LocationController;
use App\Http\Requests\TravelRequest;
use App\Models\SolarSystem;
use Illuminate\Http\Request;

/**
 * Controller for space navigation within a solar system
 */
class SpaceController extends LocationController
{
    /**
     * Display the space view
     */
    public function index(Request $request, SolarSystem $system)
    {
        $player = $this->getPlayer($request);
        $currentLocation = $this->getCurrentLocation($request);

        // Get nearby entities within scanner range
        $scannerRange = $player->ship->scanner_range ?? 1000;

        $nearbyStations = $system->stations()
            ->get()
            ->filter(fn($station) =>
                $this->navigationService->isInScannerRange($currentLocation, $station, $scannerRange)
            );

        $nearbyShips = $system->ships()
            ->where('user_id', '!=', $player->id)
            ->get()
            ->filter(fn($ship) =>
                $this->navigationService->isInScannerRange($currentLocation, $ship, $scannerRange)
            );

        $objectsInScannerRange = collect();

        foreach ($nearbyStations as $station) {
            $objectsInScannerRange->push([
                'id' => $station->slug,
                'name' => $station->name,
                'type' => 'station',
                'distance' => $this->navigationService->getDistance($currentLocation, $station),
            ]);
        }

        foreach ($nearbyShips as $ship) {
            $objectsInScannerRange->push([
                'id' => $ship->id, // Ships might not have slugs yet, keep ID or add slug to ships too
                'name' => $ship->name,
                'type' => 'ship',
                'distance' => $this->navigationService->getDistance($currentLocation, $ship),
            ]);
        }
        
        // Add planets/moons if needed
        foreach ($system->planets as $planet) {
             if ($this->navigationService->isInScannerRange($currentLocation, $planet, $scannerRange)) {
                $objectsInScannerRange->push([
                    'id' => $planet->slug,
                    'name' => $planet->name,
                    'type' => 'planet',
                    'distance' => $this->navigationService->getDistance($currentLocation, $planet),
                ]);
             }
        }

        return $this->viewWithLocation('space.index', [
            'system' => $system,
            'objectsInScannerRange' => $objectsInScannerRange->sortBy('distance'),
            'scannerRange' => $scannerRange,
        ]);
    }

    /**
     * Display navigation interface
     */
    public function navigate(Request $request, SolarSystem $system)
    {
        return $this->viewWithLocation('space.navigate', [
            'system' => $system,
        ]);
    }

    /**
     * Handle travel to a location
     */
    public function travel(TravelRequest $request, SolarSystem $system)
    {
        $player = $this->getPlayer($request);
        $validated = $request->validated();

        // Resolve destination
        $destinationType = $validated['destination_type'];
        $destinationId = $validated['destination_id']; // This will be slug for most, ID for ships

        $destination = $this->resolveDestination($destinationType, $destinationId);

        if (!$destination) {
            return back()->with('error', 'Destino no encontrado.');
        }

        // Check if player can navigate
        if (!$this->navigationService->canNavigate($player, $destination)) {
            return back()->with('error', 'No puedes navegar a este destino. Verifica combustible y requisitos.');
        }

        // Calculate and consume fuel
        $currentLocation = $this->getCurrentLocation($request);
        $fuelRequired = $this->navigationService->calculateFuelRequired($currentLocation, $destination);

        $player->ship->fuel -= $fuelRequired;
        $player->ship->save();

        // Update location
        $this->updatePlayerLocation($request, $destination);

        // Log navigation
        $this->navigationService->logNavigation($player, $currentLocation, $destination);

        return redirect()
            ->route($this->getRouteForDestination($destination), $destination)
            ->with('success', sprintf('Has viajado a %s', $destination->getDisplayName()));
    }

    /**
     * Resolve destination from type and ID/Slug
     */
    private function resolveDestination(string $type, string|int $id)
    {
        return match($type) {
            'station' => \App\Models\Station::where('slug', $id)->first() ?? \App\Models\Station::find($id),
            'planet' => \App\Models\Planet::where('slug', $id)->first() ?? \App\Models\Planet::find($id),
            'stargate' => \App\Models\Stargate::find($id), // Stargates might not have slugs yet
            'ship' => \App\Models\Ship::find($id),
            default => null,
        };
    }

    /**
     * Get route name for destination
     */
    private function getRouteForDestination($destination): string
    {
        return match(get_class($destination)) {
            \App\Models\Station::class => 'station.show',
            \App\Models\Planet::class => 'planet.show',
            \App\Models\Stargate::class => 'stargate.show',
            default => 'dashboard',
        };
    }
}
