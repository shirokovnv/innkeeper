<?php

namespace Shirokovnv\Innkeeper;

use Illuminate\Support\Collection;
use Shirokovnv\Innkeeper\Contracts\Bookable;
use Shirokovnv\Innkeeper\Contracts\Innkeepable;
use Shirokovnv\Innkeeper\Models\Booking;

class Innkeeper implements Innkeepable
{
    /**
     * @inheritDoc
     */
    public function canBook(Bookable $bookable, \DateTimeInterface $started_at, \DateTimeInterface $ended_at): bool
    {
        return !$bookable->bookings()
            ->where(function ($query) use ($started_at, $ended_at) {
                $query->where('started_at', '>', $started_at->format('Y-m-d H:i:s'))
                    ->where('started_at', '<', $ended_at->format('Y-m-d H:i:s'));
            })
            ->orWhere(function ($query) use ($started_at, $ended_at) {
                $query->where('ended_at', '>', $started_at->format('Y-m-d H:i:s'))
                    ->where('ended_at', '<', $ended_at->format('Y-m-d H:i:s'));
            })
            ->orWhere(function ($query) use ($started_at, $ended_at) {
                $query->where('started_at', '<', $started_at->format('Y-m-d H:i:s'))
                    ->where('ended_at', '>', $ended_at->format('Y-m-d H:i:s'));
            })
            ->orWhere(function ($query) use ($started_at, $ended_at) {
                $query->where('started_at', '=', $started_at->format('Y-m-d H:i:s'))
                    ->where('ended_at', '=', $ended_at->format('Y-m-d H:i:s'));
            })
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function book(
        Bookable $bookable,
        string $hash,
        \DateTimeInterface $started_at,
        \DateTimeInterface $ended_at
    ): Booking {
        /* @phpstan-ignore-next-line */
        return $bookable->bookings()->create(
            [
                'bookable_id' => $bookable->getId(),
                'bookable_type' => $bookable->getType(),
                'hash' => $hash,
                'started_at' => $started_at->format('Y-m-d H:i:s'),
                'ended_at' => $ended_at->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function all(Bookable $bookable): Collection
    {
        return $bookable->bookings()->get();
    }

    /**
     * @inheritDoc
     */
    public function first(Bookable $bookable): ?Booking
    {
        $booking = $bookable->bookings()
            ->orderBy('started_at')
            ->first();

        return $booking instanceof Booking ? $booking : null;
    }

    /**
     * @inheritDoc
     */
    public function last(Bookable $bookable): ?Booking
    {
        $booking = $bookable->bookings()
            ->latest('ended_at')
            ->first();

        return $booking instanceof Booking ? $booking : null;
    }

    /**
     * @inheritDoc
     */
    public function deleteByRange(Bookable $bookable, \DateTimeInterface $started_at, \DateTimeInterface $ended_at): void
    {
        $bookable->bookings()
            ->where('started_at', '=', $started_at->format('Y-m-d H:i:s'))
            ->where('ended_at', '=', $ended_at->format('Y-m-d H:i:s'))
            ->delete();
    }

    /**
     * @inheritDoc
     */
    public function deleteByHash(Bookable $bookable, string $hash): void
    {
        $bookable->bookings()
            ->where('hash', '=', $hash)
            ->delete();
    }
}
