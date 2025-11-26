<?php

namespace App\Services;

class NameGeneratorService
{
    protected array $firstNames = [
        'Orion', 'Lyra', 'Vega', 'Altair', 'Nova', 'Caelum', 'Rigel', 'Siri', 'Kael', 'Jara',
        'Zane', 'Xander', 'Kira', 'Jax', 'Ryla', 'Taron', 'Vex', 'Nyx', 'Cade', 'Mira',
        'Elara', 'Thorne', 'Vance', 'Seren', 'Kian', 'Liora', 'Dax', 'Rane', 'Zola', 'Kaelen'
    ];

    protected array $lastNames = [
        'Voss', 'Riker', 'Thorne', 'Stark', 'Vance', 'Kael', 'Zane', 'Xander', 'Kira', 'Jax',
        'Solari', 'Lunaris', 'Void', 'Nebula', 'Star', 'Comet', 'Meteor', 'Aster', 'Pulsar', 'Quasar',
        'Drift', 'Flux', 'Core', 'Rim', 'Sector', 'Vector', 'Matrix', 'Cipher', 'Code', 'Data'
    ];

    public function generate(): string
    {
        $firstName = $this->firstNames[array_rand($this->firstNames)];
        $lastName = $this->lastNames[array_rand($this->lastNames)];

        return "$firstName $lastName";
    }
}
