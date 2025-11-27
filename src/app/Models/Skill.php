<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'multiplier',
        'base_xp',
        'icon',
    ];

    public function dependencies()
    {
        return $this->belongsToMany(
            Skill::class,
            'skill_dependencies',
            'skill_id',
            'required_skill_id'
        )->withPivot('required_level');
    }

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_skills')
            ->withPivot('level', 'xp', 'injected_at')
            ->withTimestamps();
    }
}
