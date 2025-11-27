<?php

namespace App\Http\Controllers\Ship;

use App\Http\Controllers\LocationController;
use App\Enums\LocationType;
use Illuminate\Http\Request;

class ShipController extends LocationController
{
    /**
     * Display the ship's cockpit (main flight interface)
     */
    public function cockpit(Request $request)
    {
        $player = $this->getPlayer($request);
        $ship = $player->ship;

        if (!$ship) {
            return redirect()->route('dashboard')->with('error', 'No tienes una nave activa.');
        }

        // Verify player is actually IN the ship
        // We check if location type is SHIP. ID check matches the pivot ID (UserShip id)
        if ($player->current_location_type !== LocationType::SHIP->value || 
            (int)$player->current_location_id !== (int)$ship->pivot->id) {
             return redirect()->route('dashboard');
        }

        return $this->viewWithLocation('ship.cockpit', [
            'ship' => $ship,
            'userShip' => $ship->pivot,
            'system' => $ship->pivot->solarSystem, // Use pivot relationship
        ]);
    }
}
