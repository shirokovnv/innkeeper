<?php

namespace Shirokovnv\Innkeeper;

use Illuminate\Support\Collection;
use Shirokovnv\Innkeeper\Contracts\Bookable;
use Shirokovnv\Innkeeper\Contracts\Innkeepable;
use Shirokovnv\Innkeeper\Exceptions\WrongDateIntervalException;
use Shirokovnv\Innkeeper\Models\Booking;

class Innkeeper implements Innkeepable
{
    /**
     * @inheritDoc
     */
    public function canBook(Bookable $bookable, \DateTimeInterface $started_at, \DateTimeInterface $ended_at): bool
    {
        if ($started_at > $ended_at) {
            return false;
        }

        return !$bookable->bookings()
            ->where(function ($query) use ($started_at, $ended_at) {
                $query->where('started_at', '>', $started_at->format(Constants::MYSQL_DATE_FORMAT))
                    ->where('started_at', '<', $ended_at->format(Constants::MYSQL_DATE_FORMAT));
            })
            ->orWhere(function ($query) use ($started_at, $ended_at) {
                $query->where('ended_at', '>', $started_at->format(Constants::MYSQL_DATE_FORMAT))
                    ->where('ended_at', '<', $ended_at->format(Constants::MYSQL_DATE_FORMAT));
            })
            ->orWhere(function ($query) use ($started_at, $ended_at) {
                $query->where('started_at', '<', $started_at->format(Constants::MYSQL_DATE_FORMAT))
                    ->where('ended_at', '>', $ended_at->format(Constants::MYSQL_DATE_FORMAT));
            })
            ->orWhere(function ($query) use ($started_at, $ended_at) {
                $query->where('started_at', '=', $started_at->format(Constants::MYSQL_DATE_FORMAT))
                    ->where('ended_at', '=', $ended_at->format(Constants::MYSQL_DATE_FORMAT));
            })
            ->exists();
    }

    /**
     * @inheritDoc
     * @throws WrongDateIntervalException
     */
    public function book(
        Bookable $bookable,
        string $hash,
        \DateTimeInterface $started_at,
        \DateTimeInterface $ended_at
    ): Booking {
        if ($started_at > $ended_at) {
            throw new WrongDateIntervalException(
                'Wrong booking date interval: start date should be less than end date.'
            );
        }

        /* @phpstan-ignore-next-line */
        return $bookable->bookings()->create(
            [
                'bookable_id' => $bookable->getId(),
                'bookable_type' => $bookable->getType(),
                'hash' => $hash,
                'started_at' => $started_at->format(Constants::MYSQL_DATE_FORMAT),
                'ended_at' => $ended_at->format(Constants::MYSQL_DATE_FORMAT),
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
            ->where('started_at', '=', $started_at->format(Constants::MYSQL_DATE_FORMAT))
            ->where('ended_at', '=', $ended_at->format(Constants::MYSQL_DATE_FORMAT))
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
