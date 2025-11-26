<?php

use App\Http\Controllers\CharacterCreationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [StationController::class, 'index'])->name('dashboard'); // Redirect dashboard to station for now
    Route::get('/station/{station}/module/{module}', [StationController::class, 'show'])->name('station.show');
    Route::post('/station/move/{module}', [StationController::class, 'move'])->name('station.move')->middleware('has_character');

    Route::get('/license/create', [CharacterCreationController::class, 'create'])->name('character.create');
    Route::post('/license/store', [CharacterCreationController::class, 'store'])->name('character.store');
    Route::get('/api/name-generator', [CharacterCreationController::class, 'generateName'])->name('api.name-generator');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
