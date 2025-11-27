<?php

namespace App\Http\Middleware;

use App\Enums\LocationType;
use App\Services\LocationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to ensure player is in the correct location to access a route
 */
class EnsurePlayerLocation
{
    public function __construct(
        private LocationService $locationService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$allowedTypes): Response
    {
        $player = $request->user();

        if (!$player) {
            return redirect()->route('login');
        }

        $currentLocation = $this->locationService->getCurrentLocation($player);

        if (!$currentLocation) {
            return redirect()->route('dashboard')
                ->with('error', 'No se pudo determinar tu ubicación actual.');
        }

        // If no specific types are required, allow access
        if (empty($allowedTypes)) {
            return $next($request);
        }

        // Check if current location type is allowed
        $currentType = $currentLocation->getLocationType()->value;

        if (!in_array($currentType, $allowedTypes)) {
            $allowedLabels = array_map(
                fn($type) => LocationType::from($type)->label(),
                $allowedTypes
            );

            return redirect()->back()
                ->with('error', sprintf(
                    'Debes estar en %s para acceder a esta área. Ubicación actual: %s',
                    implode(' o ', $allowedLabels),
                    $currentLocation->getDisplayName()
                ));
        }

        // Share current location with the view
        $request->attributes->set('current_location', $currentLocation);

        return $next($request);
    }
}
