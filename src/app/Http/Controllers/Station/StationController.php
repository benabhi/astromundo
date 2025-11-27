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

        // Check if player's current location is THIS station
        $isAtStation = $player->current_location_type === \App\Enums\LocationType::STATION->value 
                    && (int)$player->current_location_id === (int)$station->id;

        // If player is at the station and has a module assigned, redirect to that module
        if ($isAtStation && $player->character->station_module_id) {
            $currentModule = $player->character->currentModule;
            
            return redirect()->route('station.module', [
                'station' => $station,
                'module' => $currentModule
            ]);
        }

        // Otherwise, show external station view (for players in orbit or space)
        return view('station.index', [
            'station' => $station,
            'isDocked' => $isAtStation,
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

            // Update player location and clear action
            $player->current_location_type = \App\Enums\LocationType::STATION;
            $player->current_location_id = $station->id;
            $player->current_ship_id = null;
            $player->current_action = null;
            $player->action_started_at = null;
            $player->save();
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
            $userShip->save();

            // Update PLAYER location to SHIP and set piloting action
            $player->current_location_type = \App\Enums\LocationType::SHIP;
            $player->current_location_id = $userShip->id;
            $player->current_ship_id = $userShip->id;
            $player->current_action = 'piloting';
            $player->action_started_at = now();
            $player->save();

            return redirect()
                ->route('ship.cockpit')
                ->with('success', 'Has desatracado. Bienvenido al puente de mando.');
        }

        return back()->with('error', 'Error al desatracar de la estación.');
    }
}
