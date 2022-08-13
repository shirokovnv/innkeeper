<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Contracts\Innkeepable;
use Shirokovnv\Innkeeper\Innkeeper;
use Shirokovnv\Innkeeper\Models\Booking;
use Shirokovnv\Innkeeper\Tests\Room;
use Shirokovnv\Innkeeper\Tests\TestCase;

abstract class InnkeeperTestCase extends TestCase
{
    /**
     * @return Innkeepable
     */
    protected function getInnkeeper(): Innkeepable
    {
        return new Innkeeper();
    }

    /**
     * Create a few bookings for the specific room with initial `start` and `end` date.
     *
     * @param int $room_id
     * @param \DateTimeInterface $initial_date
     * @param int $interval_in_hours
     * @param int $count_bookings
     * @param int $step_in_hours
     * @return void
     */
    protected function createBookingsForRoom(
        int $room_id,
        \DateTimeInterface $initial_date,
        int $interval_in_hours,
        int $count_bookings,
        int $step_in_hours = 0
    ) {
        $started_at = Carbon::parse($initial_date)->toImmutable();
        $ended_at = $started_at->addHours($interval_in_hours)->toImmutable();

        for ($i = 0; $i < $count_bookings; $i++) {
            Booking::newFactory()->create(
                [
                    'bookable_id' => $room_id,
                    'bookable_type' => Room::class,
                    'started_at' => $started_at,
                    'ended_at' => $ended_at,
                ]
            );

            $started_at = $ended_at->addHours($step_in_hours);
            $ended_at = $started_at->addHours($interval_in_hours);
        }
    }
}
