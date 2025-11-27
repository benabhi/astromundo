<?php

namespace App\Http\Controllers\Station;

use App\Enums\ModuleType;
use App\Http\Controllers\LocationController;
use App\Models\Station;
use Illuminate\Http\Request;

/**
 * Controller for station module access
 */
class StationModuleController extends LocationController
{
    /**
     * Display a specific module
     */
    /**
     * Display a specific module
     */
    public function show(Request $request, Station $station, \App\Models\StationModule $module)
    {
        $player = $this->getPlayer($request);

        // Validate module belongs to station
        if ($module->station_id !== $station->id) {
            abort(404, 'Módulo no pertenece a esta estación');
        }

        // Check if player is at the station (docked)
        if ($player->current_location_type !== \App\Enums\LocationType::STATION->value || 
            (int)$player->current_location_id !== (int)$station->id) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Debes estar atracado para acceder a los módulos de la estación.');
        }

        // Convert string type to Enum
        try {
            $moduleType = ModuleType::from($module->type);
        } catch (\ValueError $e) {
            abort(500, 'Tipo de módulo inválido: ' . $module->type);
        }

        // Check permissions if required
        if ($moduleType->requiresPermission()) {
            $requiredPermission = $moduleType->requiredPermissionLevel();
            if (!$player->hasPermission($requiredPermission)) {
                return back()->with('error', 'No tienes permisos para acceder a este módulo.');
            }
        }

        // Update player's current module
        $player->character->station_module_id = $module->id;
        $player->character->save();

        // Route to specific module view
        return $this->routeToModule($request, $station, $moduleType);
    }

    /**
     * Route to specific module implementation
     */
    /**
     * Route to specific module implementation
     */
    private function routeToModule(Request $request, Station $station, ModuleType $module)
    {
        // For now, all modules use the same view structure
        // Specific data loading can be added here if needed
        
        $data = [];

        if ($module === ModuleType::MARKET) {
            $data['items'] = $station->marketItems()->get();
        } elseif ($module === ModuleType::SHIPYARD) {
            $data['ships'] = $station->availableShips()->get();
        } elseif ($module === ModuleType::HANGAR) {
            // Player ships are loaded in renderStationView
        }

        return $this->renderStationView($request, $station, $module, $data);
    }

    /**
     * Render the unified station view with module context
     */
    private function renderStationView(Request $request, Station $station, ModuleType $moduleType, array $data = [])
    {
        $player = $this->getPlayer($request);
        
        // Common data for station view
        $viewData = array_merge([
            'station' => $station,
            'currentModule' => $player->character->currentModule, // Reload from DB to get relationship
            'modules' => $station->modules()->get(),
            'isDocked' => $station->isShipDocked($player->ship),
            'localPilots' => $station->characters()->where('id', '!=', $player->character->id)->get(),
            'dockingBaysAvailable' => $station->getAvailableDockingBays(),
            'ships' => $player->character->ships, // Always pass ships for Hangar or quick access
        ], $data);

        return $this->viewWithLocation('station.show', $viewData);
    }
}
