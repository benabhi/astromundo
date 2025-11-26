<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'multiplier'];

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_skills')
            ->withPivot('level', 'xp')
            ->withTimestamps();
    }

    public function dependencies()
    {
        return $this->belongsToMany(Skill::class, 'skill_dependencies', 'skill_id', 'required_skill_id')
            ->withPivot('required_level');
    }
}
