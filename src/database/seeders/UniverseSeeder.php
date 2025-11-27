<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UniverseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- SYSTEM 1: HELIOS (Miner Hub) ---
        $helios = \App\Models\SolarSystem::create(['name' => 'Helios', 'slug' => Str::slug('Helios'), 'coords_x' => 0, 'coords_y' => 0]);
        $helios->stars()->create(['name' => 'Helios Prime', 'type' => 'Yellow Main Sequence', 'attributes' => ['luminosity' => 1.0]]);
        
        $aethelgard = $helios->planets()->create(['name' => 'Aethelgard', 'slug' => Str::slug('Aethelgard'), 'type' => 'Gas Giant', 'orbit_index' => 4]);
        $terran = $helios->planets()->create(['name' => 'Terran', 'slug' => Str::slug('Terran'), 'type' => 'Terrestrial', 'orbit_index' => 3]);
        
        $luna = $terran->moons()->create(['name' => 'Luna', 'slug' => Str::slug('Luna'), 'type' => 'Rocky']);
        $titan = $aethelgard->moons()->create(['name' => 'Titan', 'slug' => Str::slug('Titan'), 'type' => 'Icy']);

        // Station Alpha (Miner Start)
        $stationAlpha = $luna->stations()->create([
            'name' => 'Estación Alfa', 
            'slug' => Str::slug('Estación Alfa'),
            'type' => 'NPC', 
            'solar_system_id' => $helios->id, 
            'description' => 'Un puesto industrial robusto aferrado a la superficie craterizada de Luna. El aire huele a ozono y fluido hidráulico reciclado. Chispas caen de los conductos superiores mientras los drones transportan mineral crudo desde las minas de abajo. Es un lugar de trabajo duro y vida aún más dura.'
        ]);
        $stationAlpha->modules()->createMany([
            [
                'name' => 'Cubierta de Habitación', 
                'slug' => Str::slug('Cubierta de Habitación'),
                'type' => 'quarters', 
                'description' => 'Literas estrechas pero funcionales se alinean en las paredes, vibrando ligeramente con los sistemas de soporte vital de la estación. Terminales personales brillan suavemente en la luz tenue, ofreciendo la única conexión con el mundo exterior.'
            ],
            [
                'name' => 'Hangar Principal', 
                'slug' => Str::slug('Hangar Principal'),
                'type' => 'hangar', 
                'description' => 'La cavernosa bahía del hangar resuena con el estruendo de abrazaderas magnéticas y el siseo del refrigerante. Barcazas mineras entran y salen del escudo atmosférico, con sus cascos marcados por el polvo de asteroides.'
            ],
            [
                'name' => 'El Pico Oxidado', 
                'slug' => Str::slug('El Pico Oxidado'),
                'type' => 'bar', 
                'description' => 'Una neblina de humo flota en el aire de este bar de mala muerte de baja gravedad. Los mineros apuestan sus créditos en juegos de dados holográficos mientras una vieja máquina de discos toca jazz rayado de la Vieja Tierra.'
            ],
            [
                'name' => 'Intercambio Mineral', 
                'slug' => Str::slug('Intercambio Mineral'),
                'type' => 'market', 
                'description' => 'Las pantallas parpadean con precios de mineral en tiempo real de todo el sector. Los comerciantes gritan ofertas sobre el ruido de las máquinas de teletipo, y se hacen o pierden fortunas con la fluctuación de un solo punto porcentual.'
            ],
        ]);

        // --- SYSTEM 2: PROXIMA (Transporter Hub) ---
        $proxima = \App\Models\SolarSystem::create(['name' => 'Proxima', 'slug' => Str::slug('Proxima'), 'coords_x' => 10, 'coords_y' => 5]);
        $proxima->stars()->create(['name' => 'Proxima Centauri', 'type' => 'Red Dwarf', 'attributes' => ['luminosity' => 0.0017]]);
        
        $vulcan = $proxima->planets()->create(['name' => 'Vulcan', 'slug' => Str::slug('Vulcan'), 'type' => 'Lava World', 'orbit_index' => 1]);
        $atlas = $proxima->planets()->create(['name' => 'Atlas', 'slug' => Str::slug('Atlas'), 'type' => 'Super Earth', 'orbit_index' => 2]);
        
        $phobos = $atlas->moons()->create(['name' => 'Phobos', 'slug' => Str::slug('Phobos'), 'type' => 'Rocky']);

        // Sector 9 (Transporter Start)
        $sector9 = $phobos->stations()->create([
            'name' => 'Sector 9', 
            'slug' => Str::slug('Sector 9'),
            'type' => 'NPC', 
            'solar_system_id' => $proxima->id, 
            'description' => 'Un anillo orbital reluciente que rodea a Fobos, el Sector 9 es la joya de las rutas comerciales. Anuncios de neón se reflejan en los pasillos de duracero pulido, y los vestíbulos están llenos de viajeros de una docena de sistemas.'
        ]);
        $sector9->modules()->createMany([
            [
                'name' => 'Cubierta de Habitación', 
                'slug' => Str::slug('Cubierta de Habitación'),
                'type' => 'quarters', 
                'description' => 'Apartamentos espaciosos con tragaluces simulados que ofrecen vistas de la nebulosa. El aire está perfumado con lavanda sintética y el revestimiento de gravedad está calibrado para el máximo confort.'
            ],
            [
                'name' => 'Muelle Central', 
                'slug' => Str::slug('Muelle Central'),
                'type' => 'hangar', 
                'description' => 'Una inmensa cúpula presurizada capaz de dar servicio a naves capitales. Cargadores automatizados mueven contenedores de carga con precisión de ballet, y los oficiales de aduanas son sorprendentemente educados.'
            ],
            [
                'name' => 'Gran Bazar', 
                'slug' => Str::slug('Gran Bazar'),
                'type' => 'market', 
                'description' => 'Un laberinto de puestos y boutiques que venden de todo, desde especias exóticas hasta cibernética ilegal. El zumbido del comercio es un ruido de fondo constante y relajante.'
            ],
        ]);

        // --- SYSTEM 3: KEPLER (Bounty Hunter Hub) ---
        $kepler = \App\Models\SolarSystem::create(['name' => 'Kepler', 'slug' => Str::slug('Kepler'), 'coords_x' => -15, 'coords_y' => 20]);
        $kepler->stars()->create(['name' => 'Kepler-186', 'type' => 'Red Dwarf', 'attributes' => ['luminosity' => 0.04]]);
        
        $fenris = $kepler->planets()->create(['name' => 'Fenris', 'slug' => Str::slug('Fenris'), 'type' => 'Ice Giant', 'orbit_index' => 5]);
        $nyx = $fenris->moons()->create(['name' => 'Nyx', 'slug' => Str::slug('Nyx'), 'type' => 'Rocky']);

        // Deep Void (Bounty Hunter Start)
        $deepVoid = $nyx->stations()->create([
            'name' => 'Vacío Profundo', 
            'slug' => Str::slug('Vacío Profundo'),
            'type' => 'NPC', 
            'solar_system_id' => $kepler->id, 
            'description' => 'Escondida en la sombra de Nyx, esta estación no aparece en las cartas oficiales. Los pasillos son oscuros, húmedos y silenciosos. Es un santuario para aquellos que desean permanecer invisibles.'
        ]);
        $deepVoid->modules()->createMany([
            [
                'name' => 'Cubierta de Habitación', 
                'slug' => Str::slug('Cubierta de Habitación'),
                'type' => 'quarters', 
                'description' => 'Celdas austeras con cerraduras reforzadas. El único sonido es el goteo de la condensación y el grito lejano ocasional. La privacidad está garantizada, por un precio.'
            ],
            [
                'name' => 'Muelle de las Sombras', 
                'slug' => Str::slug('Muelle de las Sombras'),
                'type' => 'hangar', 
                'description' => 'Las abrazaderas de atraque son magnéticas y silenciosas. Las naves llegan y salen sin planes de vuelo, con sus transpondedores desactivados. Los mecánicos aquí no hacen preguntas.'
            ],
            [
                'name' => 'Tablón de Recompensas', 
                'slug' => Str::slug('Tablón de Recompensas'),
                'type' => 'administration', 
                'description' => 'Un proyector holográfico muestra los rostros de los más buscados de la galaxia. Los cazadores se reúnen aquí para intercambiar consejos y afilar sus cuchillas. El aire está cargado de tensión y oportunidad.'
            ],
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
