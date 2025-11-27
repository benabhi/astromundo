<?php

namespace App\Http\Middleware;

use App\Services\LocationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to track and share player location across requests
 */
class TrackPlayerLocation
{
    public function __construct(
        private LocationService $locationService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $player = $request->user();

        if ($player) {
            $currentLocation = $this->locationService->getCurrentLocation($player);

            if ($currentLocation) {
                // Share location data with all views
                View::share('currentLocation', $currentLocation);
                View::share('locationBreadcrumb', $this->locationService->getLocationBreadcrumb($currentLocation));

                // Add to request for controller access
                $request->attributes->set('current_location', $currentLocation);
            }
        }

        return $next($request);
    }
}
