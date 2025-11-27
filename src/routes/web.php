<?php

use App\Http\Controllers\CharacterCreationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Station\StationController;
use App\Http\Controllers\Station\StationModuleController;
use App\Http\Controllers\System\SpaceController;
use App\Http\Controllers\System\SystemController;
use App\Http\Controllers\Celestial\PlanetController;
use App\Http\Controllers\Celestial\MoonController;
use App\Http\Controllers\System\StargateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación y creación de personaje
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/license/create', [CharacterCreationController::class, 'create'])->name('character.create');
    Route::post('/license/store', [CharacterCreationController::class, 'store'])->name('character.store');
    Route::get('/api/name-generator', [CharacterCreationController::class, 'generateName'])->name('api.name-generator');
});

// Rutas principales del juego
Route::middleware(['auth', 'verified', 'has_character', 'track_location'])->group(function () {
    
    // Dashboard (redirige según ubicación actual)
    // Dashboard (redirige según ubicación actual)
    Route::get('/dashboard', function () {
        $user = request()->user();
        
        if ($user->current_location_type && $user->current_location_id) {
            $type = $user->current_location_type;
            $id = $user->current_location_id;

            switch ($type) {
                case \App\Enums\LocationType::SYSTEM->value:
                    $system = \App\Models\SolarSystem::find($id);
                    return $system ? redirect()->route('system.show', $system) : redirect()->route('system.show', 'helios');
                
                case \App\Enums\LocationType::STATION->value:
                    $station = \App\Models\Station::find($id);
                    return $station ? redirect()->route('station.show', $station) : redirect()->route('system.show', 'helios');
                
                case \App\Enums\LocationType::PLANET->value:
                    $planet = \App\Models\Planet::find($id);
                    return $planet ? redirect()->route('planet.show', $planet) : redirect()->route('system.show', 'helios');
                
                case \App\Enums\LocationType::MOON->value:
                    $moon = \App\Models\Moon::find($id);
                    return $moon ? redirect()->route('moon.show', $moon) : redirect()->route('system.show', 'helios');

                case \App\Enums\LocationType::SHIP->value:
                    return redirect()->route('ship.cockpit');
                
                case \App\Enums\LocationType::SPACE->value:
                    // Space usually relates to a system
                    $system = \App\Models\SolarSystem::find($id);
                    return $system ? redirect()->route('system.space.index', $system) : redirect()->route('system.show', 'helios');
                
                default:
                    return redirect()->route('system.show', 'helios');
            }
        }

        // Default start location (System 1 -> Helios)
        return redirect()->route('system.show', 'helios'); 
    })->name('dashboard');

    // ==========================================
    // SISTEMAS SOLARES
    // ==========================================
    Route::prefix('system/{system}')->name('system.')->group(function () {
        Route::get('/', [SystemController::class, 'show'])->name('show');
        Route::get('/map', [SystemController::class, 'map'])->name('map');
        
        // Espacio abierto dentro del sistema
        Route::prefix('space')->name('space.')->group(function () {
            Route::get('/', [SpaceController::class, 'index'])->name('index');
            Route::get('/navigate', [SpaceController::class, 'navigate'])->name('navigate');
            Route::post('/travel', [SpaceController::class, 'travel'])->name('travel');
        });
    });

    // ==========================================
    // ESTACIONES ESPACIALES
    // ==========================================
    Route::prefix('station/{station}')->name('station.')->group(function () {
        Route::get('/', [StationController::class, 'show'])->name('show');
        
        // Atraque y desatraque
        Route::get('/docking', [StationController::class, 'dockingBay'])->name('docking');
        Route::post('/dock', [StationController::class, 'dock'])->name('dock');
        Route::post('/undock', [StationController::class, 'undock'])->name('undock');

        // Módulos de la estación
        Route::get('/module/{module}', [StationModuleController::class, 'show'])
            ->name('module')
            ->scopeBindings();
    });

    // ==========================================
    // SHIP ROUTES
    // ==========================================
    Route::prefix('ship')->name('ship.')->group(function () {
        Route::get('/cockpit', [\App\Http\Controllers\Ship\ShipController::class, 'cockpit'])->name('cockpit');
    });

    // ==========================================
    // PLANETAS Y LUNAS
    // ==========================================
    Route::prefix('planet/{planet}')->name('planet.')->group(function () {
        Route::get('/', [PlanetController::class, 'show'])->name('show');
        Route::get('/orbit', [PlanetController::class, 'orbit'])->name('orbit');
        Route::post('/land', [PlanetController::class, 'land'])->name('land');
        Route::post('/launch', [PlanetController::class, 'launch'])->name('launch');
    });

    Route::prefix('moon/{moon}')->name('moon.')->group(function () {
        Route::get('/', [MoonController::class, 'show'])->name('show');
    });

    // ==========================================
    // PORTALES ESTELARES
    // ==========================================
    Route::prefix('stargate/{stargate}')->name('stargate.')->group(function () {
        Route::get('/', [StargateController::class, 'show'])->name('show');
        Route::post('/jump', [StargateController::class, 'jump'])->name('jump');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
