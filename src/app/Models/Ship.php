<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line

class Ship extends Model
{
    use HasFactory; // Added this line

    protected $fillable = ['name', 'class']; // Added this line

    public function characters() // Added this method
    {
        return $this->belongsToMany(Character::class, 'character_ships')
            ->withPivot('name', 'integrity')
            ->withTimestamps();
    }
}
