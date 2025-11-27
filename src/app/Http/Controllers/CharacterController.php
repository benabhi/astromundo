<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    public function license()
    {
        $user = Auth::user();
        $character = $user->character;

        if (!$character) {
            return redirect()->route('character.create');
        }

        return view('character.license', compact('character'));
    }

    public function skills()
    {
        $user = Auth::user();
        $character = $user->character;

        if (!$character) {
            return redirect()->route('character.create');
        }

        $character->load(['skills.dependencies']);
        $skills = $character->skills->groupBy('category');

        return view('character.skills', compact('character', 'skills'));
    }
}
