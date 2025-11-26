<?php

use App\Models\SolarSystem;
use App\Models\Station;
use App\Models\Character;
use App\Models\User;

// 1. Check Systems
$systems = SolarSystem::with('stars', 'planets.moons.stations.modules', 'stargates')->get();
echo "Systems Found: " . $systems->count() . "\n";
foreach ($systems as $system) {
    echo "- System: {$system->name} ({$system->coords_x}, {$system->coords_y})\n";
    foreach ($system->stargates as $gate) {
        echo "  - Stargate to: " . $gate->destinationSystem->name . "\n";
    }
    foreach ($system->planets as $planet) {
        foreach ($planet->moons as $moon) {
            foreach ($moon->stations as $station) {
                echo "  - Station: {$station->name} (Modules: " . $station->modules->count() . ")\n";
            }
        }
    }
}

// 2. Check Character Creation Logic (Simulation)
$minerStation = Station::where('name', 'Station Alpha')->first();
echo "\nMiner Station: {$minerStation->name}\n";
$habitation = $minerStation->modules()->where('type', 'Habitation')->first();
echo "Default Module: {$habitation->name} ({$habitation->type})\n";
