<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Bookable
{
    /**
     * Get the bookable ID.
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get the bookable type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get all the bookings.
     *
     * @return MorphMany
     */
    public function bookings(): MorphMany;
}
