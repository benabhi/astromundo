<?php

namespace App\Http\Controllers;

use App\Contracts\Locatable;
use App\Services\LocationService;
use App\Services\NavigationService;
use Illuminate\Http\Request;

/**
 * Base controller for all location-based controllers
 */
abstract class LocationController extends Controller
{
    public function __construct(
        protected LocationService $locationService,
        protected NavigationService $navigationService
    ) {}

    /**
     * Get the current location from request
     */
    protected function getCurrentLocation(Request $request): ?Locatable
    {
        return $request->attributes->get('current_location');
    }

    /**
     * Get the authenticated player
     */
    protected function getPlayer(Request $request)
    {
        return $request->user();
    }

    /**
     * Update player location
     */
    protected function updatePlayerLocation(Request $request, Locatable $location): bool
    {
        $player = $this->getPlayer($request);
        return $this->locationService->updateLocation($player, $location);
    }

    /**
     * Check if player can access a location
     */
    protected function canAccessLocation(Request $request, Locatable $location): bool
    {
        $player = $this->getPlayer($request);
        return $this->locationService->canAccessLocation($player, $location);
    }

    /**
     * Get location breadcrumb for views
     */
    protected function getLocationBreadcrumb(Locatable $location): array
    {
        return $this->locationService->getLocationBreadcrumb($location);
    }

    /**
     * Return a view with common location data
     */
    protected function viewWithLocation(string $view, array $data = [])
    {
        return view($view, array_merge($data, [
            'locationService' => $this->locationService,
            'navigationService' => $this->navigationService,
        ]));
    }
}
