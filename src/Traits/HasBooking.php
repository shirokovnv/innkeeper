<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shirokovnv\Innkeeper\Models\Booking;

trait HasBooking
{
    /**
     * Get the bookable ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the bookable type.
     *
     * @return string
     */
    public function getType(): string
    {
        return static::class;
    }

    /**
     * Get all the bookings.
     *
     * @return MorphMany<Booking>
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
