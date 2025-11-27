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
    public function show(Request $request, Station $station, string $moduleType)
    {
        $player = $this->getPlayer($request);

        // Validate module type
        try {
            $module = ModuleType::from($moduleType);
        } catch (\ValueError $e) {
            abort(404, 'Módulo no encontrado');
        }

        // Check if player is docked
        if (!$station->isShipDocked($player->ship)) {
            return redirect()
                ->route('station.show', $station)
                ->with('error', 'Debes estar atracado para acceder a los módulos de la estación.');
        }

        // Check permissions if required
        if ($module->requiresPermission()) {
            $requiredPermission = $module->requiredPermissionLevel();
            if (!$player->hasPermission($requiredPermission)) {
                return back()->with('error', 'No tienes permisos para acceder a este módulo.');
            }
        }

        // Route to specific module view
        return $this->routeToModule($request, $station, $module);
    }

    /**
     * Route to specific module implementation
     */
    private function routeToModule(Request $request, Station $station, ModuleType $module)
    {
        return match($module) {
            ModuleType::MARKET => $this->showMarket($request, $station),
            ModuleType::SHIPYARD => $this->showShipyard($request, $station),
            ModuleType::BAR => $this->showBar($request, $station),
            ModuleType::MEDICAL => $this->showMedical($request, $station),
            ModuleType::HANGAR => $this->showHangar($request, $station),
            ModuleType::ADMINISTRATION => $this->showAdministration($request, $station),
            ModuleType::QUARTERS => $this->showQuarters($request, $station),
            ModuleType::ENGINEERING => $this->showEngineering($request, $station),
            ModuleType::SECURITY => $this->showSecurity($request, $station),
            ModuleType::RESEARCH => $this->showResearch($request, $station),
            ModuleType::STORAGE => $this->showStorage($request, $station),
            ModuleType::DOCKING => $this->showDocking($request, $station),
        };
    }

    private function showMarket(Request $request, Station $station)
    {
        $items = $station->marketItems()->get();

        return $this->viewWithLocation('station.modules.market', [
            'station' => $station,
            'items' => $items,
        ]);
    }

    private function showShipyard(Request $request, Station $station)
    {
        $ships = $station->availableShips()->get();

        return $this->viewWithLocation('station.modules.shipyard', [
            'station' => $station,
            'ships' => $ships,
        ]);
    }

    private function showBar(Request $request, Station $station)
    {
        return $this->viewWithLocation('station.modules.bar', [
            'station' => $station,
        ]);
    }

    private function showMedical(Request $request, Station $station)
    {
        $player = $this->getPlayer($request);

        return $this->viewWithLocation('station.modules.medical', [
            'station' => $station,
            'playerHealth' => $player->health,
        ]);
    }

    private function showHangar(Request $request, Station $station)
    {
        $player = $this->getPlayer($request);

        return $this->viewWithLocation('station.modules.hangar', [
            'station' => $station,
            'playerShip' => $player->ship,
        ]);
    }

    private function showAdministration(Request $request, Station $station)
    {
        return $this->viewWithLocation('station.modules.administration', [
            'station' => $station,
        ]);
    }

    private function showQuarters(Request $request, Station $station)
    {
        return $this->viewWithLocation('station.modules.quarters', [
            'station' => $station,
        ]);
    }

    private function showEngineering(Request $request, Station $station)
    {
        return $this->viewWithLocation('station.modules.engineering', [
            'station' => $station,
        ]);
    }

    private function showSecurity(Request $request, Station $station)
    {
        return $this->viewWithLocation('station.modules.security', [
            'station' => $station,
        ]);
    }

    private function showResearch(Request $request, Station $station)
    {
        return $this->viewWithLocation('station.modules.research', [
            'station' => $station,
        ]);
    }

    private function showStorage(Request $request, Station $station)
    {
        $player = $this->getPlayer($request);

        return $this->viewWithLocation('station.modules.storage', [
            'station' => $station,
            'playerCargo' => $player->ship->cargo ?? [],
        ]);
    }

    private function showDocking(Request $request, Station $station)
    {
        $dockedShips = $station->getDockedShips();

        return $this->viewWithLocation('station.modules.docking', [
            'station' => $station,
            'dockedShips' => $dockedShips,
        ]);
    }
}
