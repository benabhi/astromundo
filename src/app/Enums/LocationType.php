<?php

namespace App\Enums;

enum LocationType: string
{
    case SYSTEM = 'system';
    case SPACE = 'space';
    case STATION = 'station';
    case PLANET = 'planet';
    case MOON = 'moon';
    case SHIP = 'ship';
    case ASTEROID_FIELD = 'asteroid_field';
    case STARGATE = 'stargate';
    case ORBIT = 'orbit';
    case SURFACE = 'surface';

    /**
     * Get human-readable label for the location type
     */
    public function label(): string
    {
        return match($this) {
            self::SYSTEM => 'Sistema Solar',
            self::SPACE => 'Espacio',
            self::STATION => 'Estación Espacial',
            self::PLANET => 'Planeta',
            self::MOON => 'Luna',
            self::SHIP => 'Nave',
            self::ASTEROID_FIELD => 'Campo de Asteroides',
            self::STARGATE => 'Portal Estelar',
            self::ORBIT => 'Órbita',
            self::SURFACE => 'Superficie',
        };
    }

    /**
     * Get icon class for the location type
     */
    public function icon(): string
    {
        return match($this) {
            self::SYSTEM => 'solar-system',
            self::SPACE => 'space',
            self::STATION => 'space-station',
            self::PLANET => 'planet',
            self::MOON => 'moon',
            self::SHIP => 'spaceship',
            self::ASTEROID_FIELD => 'asteroids',
            self::STARGATE => 'portal',
            self::ORBIT => 'orbit',
            self::SURFACE => 'surface',
        };
    }

    /**
     * Check if this location type can contain other locations
     */
    public function isContainer(): bool
    {
        return in_array($this, [
            self::SYSTEM,
            self::SPACE,
            self::STATION,
            self::PLANET,
            self::MOON,
            self::SHIP,
        ]);
    }

    /**
     * Get valid child location types
     */
    public function validChildren(): array
    {
        return match($this) {
            self::SYSTEM => [self::SPACE, self::PLANET, self::STARGATE],
            self::SPACE => [self::SHIP, self::STATION, self::ASTEROID_FIELD],
            self::STATION => [], // Modules are handled separately
            self::PLANET => [self::ORBIT, self::SURFACE, self::MOON],
            self::MOON => [self::ORBIT, self::SURFACE],
            default => [],
        };
    }
}
