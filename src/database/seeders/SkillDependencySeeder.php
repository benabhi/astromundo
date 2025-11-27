<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillDependency;
use Illuminate\Database\Seeder;

class SkillDependencySeeder extends Seeder
{
    public function run(): void
    {
        $dependencies = [
            'PILOTING_ADVANCED' => ['PILOTING_BASIC' => 3],
            'PILOTING_COMBAT' => ['PILOTING_ADVANCED' => 2],
            'PILOTING_CARGO' => ['PILOTING_BASIC' => 2],
            'MINING_ADVANCED' => ['MINING_BASIC' => 3],
            'REFINING' => ['MINING_BASIC' => 2],
            'MANUFACTURING' => ['REFINING' => 3],
            'NEGOTIATION' => ['TRADE_BASIC' => 2],
            'MARKET_ANALYSIS' => ['NEGOTIATION' => 2],
            'SCANNING' => ['SCIENCE_BASIC' => 2],
            'HACKING' => ['SCIENCE_BASIC' => 4],
            'GUNNERY_MEDIUM' => ['GUNNERY_SMALL' => 3],
            'DEFENSIVE_SYSTEMS' => ['PILOTING_BASIC' => 2],
        ];

        foreach ($dependencies as $skillCode => $requirements) {
            $skill = Skill::where('code', $skillCode)->first();
            
            if (!$skill) continue;

            foreach ($requirements as $reqCode => $level) {
                $reqSkill = Skill::where('code', $reqCode)->first();
                
                if (!$reqSkill) continue;

                SkillDependency::updateOrCreate(
                    [
                        'skill_id' => $skill->id,
                        'required_skill_id' => $reqSkill->id,
                    ],
                    [
                        'required_level' => $level
                    ]
                );
            }
        }
    }
}
