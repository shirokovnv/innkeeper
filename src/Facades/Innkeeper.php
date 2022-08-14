<?php

namespace Shirokovnv\Innkeeper\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Shirokovnv\Innkeeper\Contracts\Bookable;
use Shirokovnv\Innkeeper\Contracts\Innkeepable;
use Shirokovnv\Innkeeper\Models\Booking;

/**
 * @method static bool canBook(Bookable $bookable, \DateTimeInterface $started_at, \DateTimeInterface $ended_at)
 * @method static Booking book(Bookable $bookable, string $hash, \DateTimeInterface $started_at, \DateTimeInterface $ended_at)
 * @method static Collection all()
 * @method static Collection allInRange(\DateTimeInterface $started_at, \DateTimeInterface $ended_at)
 * @method static Collection allFor(Bookable $bookable)
 * @method static Collection allInRangeFor(Bookable $bookable, \DateTimeInterface $started_at, \DateTimeInterface $ended_at)
 * @method static Booking|null firstFor(Bookable $bookable)
 * @method static Booking|null lastFor(Bookable $bookable)
 * @method static void deleteByRangeFor(Bookable $bookable, \DateTimeInterface $started_at, \DateTimeInterface $ended_at)
 * @method static void deleteByHashFor(Bookable $bookable, string $hash)
 *
 * @see Innkeepable
 */
class Innkeeper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Innkeepable::class;
    }
}
