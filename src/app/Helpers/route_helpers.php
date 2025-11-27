<?php

use App\Contracts\Locatable;
use App\Enums\LocationType;

if (!function_exists('location_url')) {
    /**
     * Generate URL for a location using its slug
     */
    function location_url(Locatable $location, ?string $action = null): string
    {
        $type = $location->getLocationType();
        $slug = $location->getSlug();

        $route = match($type) {
            LocationType::SYSTEM => 'system.show',
            LocationType::STATION => 'station.show',
            LocationType::PLANET => 'planet.show',
            LocationType::MOON => 'moon.show',
            LocationType::STARGATE => 'stargate.show',
            default => 'dashboard',
        };

        if ($action) {
            $route = str_replace('.show', '.' . $action, $route);
        }

        return route($route, $slug);
    }
}

if (!function_exists('module_url')) {
    /**
     * Generate URL for a station module
     */
    function module_url($station, string $moduleType): string
    {
        $stationSlug = $station instanceof Locatable
            ? $station->getSlug()
            : $station;

        return route('station.module', [$stationSlug, $moduleType]);
    }
}

if (!function_exists('format_coordinates')) {
    /**
     * Format coordinates for display
     */
    function format_coordinates(array $coords): string
    {
        return sprintf(
            'X: %s, Y: %s, Z: %s',
            number_format($coords['x'] ?? 0, 2),
            number_format($coords['y'] ?? 0, 2),
            number_format($coords['z'] ?? 0, 2)
        );
    }
}

if (!function_exists('format_distance')) {
    /**
     * Format distance for display
     */
    function format_distance(float $distance): string
    {
        if ($distance < 1000) {
            return number_format($distance, 2) . ' u';
        }

        if ($distance < 1000000) {
            return number_format($distance / 1000, 2) . ' Ku';
        }

        return number_format($distance / 1000000, 2) . ' Mu';
    }
}

if (!function_exists('location_icon')) {
    /**
     * Get icon class for a location type
     */
    function location_icon(LocationType $type): string
    {
        return $type->icon();
    }
}

if (!function_exists('location_label')) {
    /**
     * Get label for a location type
     */
    function location_label(LocationType $type): string
    {
        return $type->label();
    }
}
