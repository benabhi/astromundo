<?php

namespace App\Contracts;

use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface for entities that have a location in the universe
 */
interface Locatable
{
    /**
     * Get the location type of this entity
     */
    public function getLocationType(): LocationType;

    /**
     * Get the parent location (if any)
     */
    public function parentLocation(): ?BelongsTo;

    /**
     * Get the full location path (breadcrumb)
     * Example: ["Alpha Centauri", "Space", "Station Alpha"]
     */
    public function getLocationPath(): array;



    /**
     * Get the slug for URL generation
     */
    public function getSlug(): string;

    /**
     * Get the display name
     */
    public function getDisplayName(): string;
}
