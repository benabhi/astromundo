<?php

namespace App\Http\Controllers\Station;

use App\Http\Controllers\LocationController;
use App\Http\Requests\DockRequest;
use App\Models\Station;
use Illuminate\Http\Request;

/**
 * Controller for space station operations
 */
class StationController extends LocationController
{
    /**
     * Display station overview
     */
    public function show(Request $request, Station $station)
    {
        $player = $this->getPlayer($request);

        // Check if player is docked
        $isDocked = $station->isShipDocked($player->ship);

        $modules = $station->modules()->get();

        // Determine current module (from player location or default to first)
        $currentModuleId = $player->character->station_module_id;
        $currentModule = $modules->firstWhere('id', $currentModuleId) ?? $modules->first();

        // If player has no module assigned but is in station, assign default
        if (!$currentModuleId && $currentModule) {
            $player->character->station_module_id = $currentModule->id;
            $player->character->save();
        }

        // Redirect to the specific module route to enforce URL structure
        return redirect()->route('station.module', [
            'station' => $station, 
            'module' => $currentModule
        ]);
    }

    /**
     * Display docking interface
     */
    public function dockingBay(Request $request, Station $station)
    {
        $player = $this->getPlayer($request);

        return $this->viewWithLocation('station.docking', [
            'station' => $station,
            'ship' => $player->ship,
            'dockingFee' => $station->getDockingFee(),
            'baysAvailable' => $station->hasDockingBaysAvailable(),
        ]);
    }

    /**
     * Dock at the station
     */
    public function dock(DockRequest $request, Station $station)
    {
        $player = $this->getPlayer($request);
        $ship = $player->ship;

        // Check if can dock
        if (!$station->canDock($player)) {
            return back()->with('error', 'No puedes atracar en esta estación.');
        }

        // Check docking bays
        if (!$station->hasDockingBaysAvailable()) {
            return back()->with('error', 'No hay bahías de atraque disponibles.');
        }

        // Check docking fee
        $dockingFee = $station->getDockingFee();
        if ($player->credits < $dockingFee) {
            return back()->with('error', 'No tienes suficientes créditos para atracar.');
        }

        // Dock the ship
        if ($station->dock($ship)) {
            // Deduct docking fee
            $player->credits -= $dockingFee;
            $player->save();

            // Update player location
            $this->updatePlayerLocation($request, $station);

            return redirect()
                ->route('station.show', $station)
                ->with('success', 'Has atracado exitosamente en ' . $station->getDisplayName());
        }

        return back()->with('error', 'Error al atracar en la estación.');
    }

    /**
     * Undock from the station
     */
    public function undock(Request $request, Station $station)
    {
        $player = $this->getPlayer($request);
        
        // Determine which ship to undock
        $shipId = $request->input('ship_id');
        $ship = $shipId 
            ? $player->character->ships()->find($shipId) 
            : $player->ship;

        if (!$ship) {
            return back()->with('error', 'Nave no encontrada o no te pertenece.');
        }

        if ($station->undock($ship)) {
            // Update SHIP location to space (at station coords)
            $userShip = $ship->pivot;
            $userShip->solar_system_id = $station->solar_system_id;
            $userShip->location_type = \App\Enums\LocationType::SPACE->value;
            $userShip->location_id = null;
            $userShip->coords_x = $station->coords_x ?? 0;
            $userShip->coords_y = $station->coords_y ?? 0;
            $userShip->save();

            // Update PLAYER location to SHIP (Cockpit)
            $player->current_location_type = \App\Enums\LocationType::SHIP;
            $player->current_location_id = $userShip->id;
            $player->save();

            return redirect()
                ->route('ship.cockpit')
                ->with('success', 'Has desatracado. Bienvenido al puente de mando.');
        }

        return back()->with('error', 'Error al desatracar de la estación.');
    }
}
