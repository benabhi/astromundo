<?php

namespace App\Services;

use App\Models\Character;
use App\Models\Skill;
use App\Notifications\SkillLevelUp;

class SkillService
{
    /**
     * Calcular XP requerida para un nivel
     */
    public function calculateRequiredXP(int $targetLevel, int $multiplier, int $baseXP = 100): int
    {
        return $baseXP * pow($multiplier, $targetLevel - 1);
    }
    
    /**
     * Otorgar XP a una habilidad
     */
    public function grantXP(Character $character, string $skillCode, int $xp): void
    {
        $characterSkill = $character->skills()
            ->where('code', $skillCode)
            ->first();
        
        if (!$characterSkill) {
            return; // No tiene la habilidad
        }
        
        $pivot = $characterSkill->pivot;
        $pivot->xp += $xp;
        
        // Verificar level-ups
        while ($this->canLevelUp($pivot, $characterSkill)) {
            $pivot->level++;
            $requiredXP = $this->calculateRequiredXP(
                $pivot->level,
                $characterSkill->multiplier,
                $characterSkill->base_xp
            );
            $pivot->xp -= $requiredXP;
            
            // Notificar (TODO: Implementar notificación)
            // $character->user->notify(
            //     new SkillLevelUp($characterSkill, $pivot->level)
            // );
        }
        
        $pivot->save();
    }
    
    /**
     * Verificar si puede subir de nivel
     */
    private function canLevelUp($pivot, Skill $skill): bool
    {
        if ($pivot->level >= 5) {
            return false;
        }
        
        $requiredXP = $this->calculateRequiredXP(
            $pivot->level + 1,
            $skill->multiplier,
            $skill->base_xp
        );
        
        return $pivot->xp >= $requiredXP;
    }
    
    /**
     * Verificar si cumple requisitos para una habilidad
     */
    public function meetsRequirements(Character $character, Skill $skill): bool
    {
        foreach ($skill->dependencies as $dependency) {
            if (!$character->hasSkill($dependency->code, $dependency->pivot->required_level)) {
                return false;
            }
        }
        
        return true;
    }
}
