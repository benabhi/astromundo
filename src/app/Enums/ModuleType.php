<?php

namespace App\Enums;

enum ModuleType: string
{
    case MARKET = 'market';
    case SHIPYARD = 'shipyard';
    case BAR = 'bar';
    case MEDICAL = 'medical';
    case HANGAR = 'hangar';
    case ADMINISTRATION = 'administration';
    case QUARTERS = 'quarters';
    case ENGINEERING = 'engineering';
    case SECURITY = 'security';
    case RESEARCH = 'research';
    case STORAGE = 'storage';
    case DOCKING = 'docking';

    /**
     * Get human-readable label for the module type
     */
    public function label(): string
    {
        return match($this) {
            self::MARKET => 'Mercado',
            self::SHIPYARD => 'Astillero',
            self::BAR => 'Bar',
            self::MEDICAL => 'Centro Médico',
            self::HANGAR => 'Hangar',
            self::ADMINISTRATION => 'Administración',
            self::QUARTERS => 'Alojamientos',
            self::ENGINEERING => 'Ingeniería',
            self::SECURITY => 'Seguridad',
            self::RESEARCH => 'Investigación',
            self::STORAGE => 'Almacén',
            self::DOCKING => 'Muelle de Atraque',
        };
    }

    /**
     * Get icon class for the module type
     */
    public function icon(): string
    {
        return match($this) {
            self::MARKET => 'store',
            self::SHIPYARD => 'construction',
            self::BAR => 'local-bar',
            self::MEDICAL => 'medical-services',
            self::HANGAR => 'garage',
            self::ADMINISTRATION => 'admin-panel-settings',
            self::QUARTERS => 'hotel',
            self::ENGINEERING => 'engineering',
            self::SECURITY => 'security',
            self::RESEARCH => 'science',
            self::STORAGE => 'inventory',
            self::DOCKING => 'dock',
        };
    }

    /**
     * Check if this module requires special permissions
     */
    public function requiresPermission(): bool
    {
        return in_array($this, [
            self::ADMINISTRATION,
            self::SECURITY,
            self::ENGINEERING,
        ]);
    }

    /**
     * Get the required permission level
     */
    public function requiredPermissionLevel(): ?string
    {
        return match($this) {
            self::ADMINISTRATION => 'station_admin',
            self::SECURITY => 'station_security',
            self::ENGINEERING => 'station_engineer',
            default => null,
        };
    }
}
