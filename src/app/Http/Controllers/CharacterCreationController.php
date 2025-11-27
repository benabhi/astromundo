<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Character;
use App\Models\Ship;
use App\Models\Skill;
use App\Services\NameGeneratorService;
use Illuminate\Support\Facades\Auth;

class CharacterCreationController extends Controller
{
    protected $nameGenerator;

    public function __construct(NameGeneratorService $nameGenerator)
    {
        $this->nameGenerator = $nameGenerator;
    }

    public function create()
    {
        // Check if user already has a character
        if (Auth::user()->character) {
            return redirect()->route('dashboard');
        }

        $initialName = $this->nameGenerator->generate();
        return view('character.create', compact('initialName'));
    }

    public function generateName()
    {
        return response()->json(['name' => $this->nameGenerator->generate()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'age' => 'required|integer|min:18|max:60',
            'role' => 'required|in:miner,transporter,hunter',
        ]);

        $user = Auth::user();

        // Get Starting Station based on Role
        $stationSlug = match ($request->role) {
            'miner' => 'estacion-alfa',
            'transporter' => 'sector-9',
            'hunter' => 'vacio-profundo',
        };

        $station = \App\Models\Station::where('slug', $stationSlug)->first();
        
        // Fallback if seeding failed or name mismatch
        if (!$station) {
            $station = \App\Models\Station::first();
        }

        if (!$station) {
            // Emergency fallback or error
            throw new \Exception("No stations found in the universe. Please run the seeder.");
        }

        // Default to Habitation module
        $module = $station->modules()->where('type', 'quarters')->first() ?? $station->modules()->first();

        // Create Character
        $character = Character::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'age' => $request->age,
            'date_of_birth' => now(),
            'station_id' => $station->id,
            'station_module_id' => $module->id,
            'happiness' => 100,
            'integrity' => 100,
            'energy' => 100,
        ]);

        // Update User Location
        $user->current_location_type = \App\Enums\LocationType::STATION->value;
        $user->current_location_id = $station->id;
        $user->save();

        // Assign Starting Assets
        $this->assignStartingAssets($character, $request->role);

        return redirect()->route('dashboard');
    }



    protected function assignStartingAssets(Character $character, $role)
    {
        // Define Assets
        $assets = match ($role) {
            'miner' => [
                'ship' => ['name' => 'Mole-class Excavator', 'class' => 'Miner'],
                'skills' => ['PILOTING_BASIC', 'MINING_BASIC'],
            ],
            'transporter' => [
                'ship' => ['name' => 'Mule-class Hauler', 'class' => 'Hauler'],
                'skills' => ['PILOTING_BASIC', 'PILOTING_CARGO', 'TRADE_BASIC'],
            ],
            'hunter' => [
                'ship' => ['name' => 'Dart-class Interceptor', 'class' => 'Fighter'],
                'skills' => ['PILOTING_BASIC', 'PILOTING_COMBAT', 'GUNNERY_SMALL'],
            ],
        };

        // Create/Get Ship Template
        $ship = Ship::firstOrCreate(
            ['name' => $assets['ship']['name']],
            ['class' => $assets['ship']['class']]
        );
        $character->ships()->attach($ship->id, ['name' => $assets['ship']['name']]);

        // Assign Skills
        foreach ($assets['skills'] as $skillCode) {
            $skill = Skill::where('code', $skillCode)->first();
            if ($skill) {
                $character->skills()->attach($skill->id, [
                    'level' => 1, 
                    'xp' => 0,
                    'injected_at' => now(),
                ]);
            }
        }
    }
}
