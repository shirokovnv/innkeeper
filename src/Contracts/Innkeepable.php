<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Contracts;

use Illuminate\Support\Collection;
use Shirokovnv\Innkeeper\Models\Booking;

interface Innkeepable
{
    /**
     * Checks for opportunity to book in a specified date range.
     *
     * @param Bookable $bookable
     * @param \DateTimeInterface $started_at
     * @param \DateTimeInterface $ended_at
     * @return bool
     */
    public function canBook(Bookable $bookable, \DateTimeInterface $started_at, \DateTimeInterface $ended_at): bool;

    /**
     * Makes a reservation in a specified date range.
     *
     * @param Bookable $bookable
     * @param string $hash
     * @param \DateTimeInterface $started_at
     * @param \DateTimeInterface $ended_at
     * @return Booking
     */
    public function book(
        Bookable $bookable,
        string $hash,
        \DateTimeInterface $started_at,
        \DateTimeInterface $ended_at
    ): Booking;

    /**
     * Get all the bookings.
     *
     * @param Bookable $bookable
     * @return Collection<Booking>
     */
    public function all(Bookable $bookable): Collection;

    /**
     * Get the earliest started booking.
     *
     * @param Bookable $bookable
     * @return Booking|null
     */
    public function first(Bookable $bookable): ?Booking;

    /**
     * Get the latest ended booking.
     *
     * @param Bookable $bookable
     * @return Booking|null
     */
    public function last(Bookable $bookable): ?Booking;

    /**
     * Delete by date range.
     *
     * @param Bookable $bookable
     * @param \DateTimeInterface $started_at
     * @param \DateTimeInterface $ended_at
     * @return void
     */
    public function deleteByRange(
        Bookable $bookable,
        \DateTimeInterface $started_at,
        \DateTimeInterface $ended_at
    ): void;

    /**
     * Delete by booking hash.
     *
     * @param Bookable $bookable
     * @param string $hash
     * @return void
     */
    public function deleteByHash(Bookable $bookable, string $hash): void;
}
