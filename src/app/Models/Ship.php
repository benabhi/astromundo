<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line

use App\Contracts\Locatable;
use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ship extends Model implements Locatable
{
    use HasFactory;

    protected $fillable = ['name', 'class', 'energy_capacity', 'current_energy'];

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_ships')
            ->withPivot('name', 'integrity')
            ->withTimestamps();
    }

    // Locatable Implementation
    public function getLocationType(): LocationType
    {
        return LocationType::SHIP;
    }

    public function parentLocation(): ?BelongsTo
    {
        return null; // Ships move around, handled by User/Character location
    }

    public function getLocationPath(): array
    {
        return [$this->name];
    }

    public function getCoordinates(): array
    {
        // Ships might not have fixed coordinates unless tracked
        return [
            'x' => 0,
            'y' => 0,
            'z' => 0,
        ];
    }

    public function getSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getDisplayName(): string
    {
        return $this->name;
    }
}
