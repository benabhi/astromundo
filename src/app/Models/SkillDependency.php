<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillDependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_id',
        'required_skill_id',
        'required_level',
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function requiredSkill()
    {
        return $this->belongsTo(Skill::class, 'required_skill_id');
    }
}
