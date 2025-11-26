<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $character = $user->character;

        if (!$character) {
            return redirect()->route('character.create');
        }

        // Ensure character has a valid location
        if (!$character->station_id || !$character->station_module_id) {
            $station = \App\Models\Station::first();
            $module = $station->modules()->where('type', 'Habitation')->first();
            
            $character->update([
                'station_id' => $station->id,
                'station_module_id' => $module->id
            ]);
        }

        // Redirect to the explicit URL for the current location
        return redirect()->route('station.show', [
            'station' => $character->station_id,
            'module' => $character->station_module_id
        ]);
    }

    public function show(\App\Models\Station $station, \App\Models\StationModule $module)
    {
        $user = Auth::user();
        $character = $user->character;

        // Security Check: Is the character actually here?
        // Later we can add "Remote Viewing" permissions, but for now, strict location check.
        if ($character->station_id !== $station->id || $character->station_module_id !== $module->id) {
            // If they are not here, redirect them to where they actually are
            return redirect()->route('station.show', [
                'station' => $character->station_id,
                'module' => $character->station_module_id
            ]);
        }

        // Fetch other pilots in the same station
        $localPilots = \App\Models\Character::where('station_id', $station->id)
            ->where('id', '!=', $character->id)
            ->limit(10)
            ->get();

        return view('station.index', [
            'character' => $character,
            'station' => $station,
            'currentModule' => $module,
            'localPilots' => $localPilots
        ]);
    }

    public function move(\App\Models\StationModule $module)
    {
        $character = auth()->user()->character;

        // Validation: Can only move to modules in the current station
        if ($module->station_id !== $character->station_id) {
            abort(403, 'Cannot move to a module in another station.');
        }

        $character->station_module_id = $module->id;
        $character->save();

        return redirect()->route('station.show', [
            'station' => $character->station_id,
            'module' => $module->id
        ]);
    }
}
