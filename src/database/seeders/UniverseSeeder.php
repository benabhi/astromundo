<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniverseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- SYSTEM 1: HELIOS (Miner Hub) ---
        $helios = \App\Models\SolarSystem::create(['name' => 'Helios', 'coords_x' => 0, 'coords_y' => 0]);
        $helios->stars()->create(['name' => 'Helios Prime', 'type' => 'Yellow Main Sequence', 'attributes' => ['luminosity' => 1.0]]);
        
        $aethelgard = $helios->planets()->create(['name' => 'Aethelgard', 'type' => 'Gas Giant', 'orbit_index' => 4]);
        $terran = $helios->planets()->create(['name' => 'Terran', 'type' => 'Terrestrial', 'orbit_index' => 3]);
        
        $luna = $terran->moons()->create(['name' => 'Luna', 'type' => 'Rocky']);
        $titan = $aethelgard->moons()->create(['name' => 'Titan', 'type' => 'Icy']);

        // Station Alpha (Miner Start)
        $stationAlpha = $luna->stations()->create(['name' => 'Station Alpha', 'type' => 'NPC', 'description' => 'The primary mining hub.']);
        $stationAlpha->modules()->createMany([
            ['name' => 'Habitation Deck', 'type' => 'Habitation', 'description' => 'Standard crew quarters.'],
            ['name' => 'Main Hangar', 'type' => 'Hangar', 'description' => 'Docking bays.'],
            ['name' => 'The Rusty Pickaxe', 'type' => 'Cantina', 'description' => 'A rough bar.'],
            ['name' => 'Mineral Exchange', 'type' => 'Market', 'description' => 'Trade raw ore.'],
        ]);

        // --- SYSTEM 2: PROXIMA (Transporter Hub) ---
        $proxima = \App\Models\SolarSystem::create(['name' => 'Proxima', 'coords_x' => 10, 'coords_y' => 5]);
        $proxima->stars()->create(['name' => 'Proxima Centauri', 'type' => 'Red Dwarf', 'attributes' => ['luminosity' => 0.0017]]);
        
        $vulcan = $proxima->planets()->create(['name' => 'Vulcan', 'type' => 'Lava World', 'orbit_index' => 1]);
        $atlas = $proxima->planets()->create(['name' => 'Atlas', 'type' => 'Super Earth', 'orbit_index' => 2]);
        
        $phobos = $atlas->moons()->create(['name' => 'Phobos', 'type' => 'Rocky']);

        // Sector 9 (Transporter Start)
        $sector9 = $phobos->stations()->create(['name' => 'Sector 9', 'type' => 'NPC', 'description' => 'A bustling trade station.']);
        $sector9->modules()->createMany([
            ['name' => 'Habitation Deck', 'type' => 'Habitation', 'description' => 'Comfortable quarters.'],
            ['name' => 'Central Dock', 'type' => 'Hangar', 'description' => 'High-capacity docking.'],
            ['name' => 'Grand Bazaar', 'type' => 'Market', 'description' => 'Interstellar goods.'],
        ]);

        // --- SYSTEM 3: KEPLER (Bounty Hunter Hub) ---
        $kepler = \App\Models\SolarSystem::create(['name' => 'Kepler', 'coords_x' => -15, 'coords_y' => 20]);
        $kepler->stars()->create(['name' => 'Kepler-186', 'type' => 'Red Dwarf', 'attributes' => ['luminosity' => 0.04]]);
        
        $fenris = $kepler->planets()->create(['name' => 'Fenris', 'type' => 'Ice Giant', 'orbit_index' => 5]);
        $nyx = $fenris->moons()->create(['name' => 'Nyx', 'type' => 'Rocky']);

        // Deep Void (Bounty Hunter Start)
        $deepVoid = $nyx->stations()->create(['name' => 'Deep Void', 'type' => 'NPC', 'description' => 'A shadowy outpost.']);
        $deepVoid->modules()->createMany([
            ['name' => 'Habitation Deck', 'type' => 'Habitation', 'description' => 'Sparse bunks.'],
            ['name' => 'Shadow Dock', 'type' => 'Hangar', 'description' => 'Discreet docking.'],
            ['name' => 'Bounty Board', 'type' => 'Office', 'description' => 'Contracts.'],
        ]);

        // --- STARGATES ---
        // Helios <-> Proxima
        $helios->stargates()->create(['name' => 'Proxima Gate', 'destination_system_id' => $proxima->id, 'orbit_index' => 10]);
        $proxima->stargates()->create(['name' => 'Helios Gate', 'destination_system_id' => $helios->id, 'orbit_index' => 10]);

        // Proxima <-> Kepler
        $proxima->stargates()->create(['name' => 'Kepler Gate', 'destination_system_id' => $kepler->id, 'orbit_index' => 11]);
        $kepler->stargates()->create(['name' => 'Proxima Gate', 'destination_system_id' => $proxima->id, 'orbit_index' => 11]);
    }
}
