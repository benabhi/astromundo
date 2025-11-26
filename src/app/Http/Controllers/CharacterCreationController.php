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

        // Create Character
        $character = Character::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'age' => $request->age,
            'date_of_birth' => now(),
            'location_id' => $this->getStartingLocation($request->role),
        ]);

        // Assign Starting Assets
        $this->assignStartingAssets($character, $request->role);

        return redirect()->route('dashboard');
    }

    protected function getStartingLocation($role)
    {
        return match ($role) {
            'miner' => 'Station Alpha',
            'transporter' => 'Sector 9 Hub',
            'hunter' => 'Deep Void Outpost',
        };
    }

    protected function assignStartingAssets(Character $character, $role)
    {
        // Define Assets
        $assets = match ($role) {
            'miner' => [
                'ship' => ['name' => 'Mole-class Excavator', 'class' => 'Miner'],
                'skill' => ['name' => 'Mining', 'code' => 'MINING', 'multiplier' => 1],
            ],
            'transporter' => [
                'ship' => ['name' => 'Mule-class Hauler', 'class' => 'Hauler'],
                'skill' => ['name' => 'Logistics', 'code' => 'LOGISTICS', 'multiplier' => 1],
            ],
            'hunter' => [
                'ship' => ['name' => 'Dart-class Interceptor', 'class' => 'Fighter'],
                'skill' => ['name' => 'Gunnery', 'code' => 'GUNNERY', 'multiplier' => 1],
            ],
        };

        // Create/Get Ship Template
        $ship = Ship::firstOrCreate(
            ['name' => $assets['ship']['name']],
            ['class' => $assets['ship']['class']]
        );
        $character->ships()->attach($ship->id, ['name' => $assets['ship']['name']]);

        // Create/Get Skill
        $skill = Skill::firstOrCreate(
            ['code' => $assets['skill']['code']],
            ['name' => $assets['skill']['name'], 'multiplier' => $assets['skill']['multiplier']]
        );
        $character->skills()->attach($skill->id, ['level' => 1, 'xp' => 0]);
    }
}
