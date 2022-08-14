<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Models\Booking;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::allInRange
 */
class AllInRangeTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testEmptyCollection(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $initial_date = Carbon::now()->toImmutable();

        $bookings = $innkeeper->allInRange($room, $initial_date->subYear(), $initial_date->addYear());
        $this->assertEquals(0, $bookings->count());
    }

    /**
     * @return void
     */
    public function testNonEmptyCollection(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $initial_date = Carbon::now()->toImmutable();

        $count_bookings = 10;

        $this->createBookingsForRoom($room->id, $initial_date, 1, $count_bookings);

        // Test query bookings in exact interval
        $bookings = $innkeeper->allInRange($room, $initial_date, $initial_date->addHours($count_bookings));

        $this->assertEquals($count_bookings, $bookings->count());
        foreach ($bookings as $booking) {
            $this->assertInstanceOf(Booking::class, $booking);
        }

        // Test query bookings in partial interval
        $bookings = $innkeeper->allInRange($room, $initial_date, $initial_date->addHour());
        $this->assertEquals(1, $bookings->count());

        // Test query bookings in before interval
        $bookings = $innkeeper->allInRange($room, $initial_date->subDay(), $initial_date);
        $this->assertEquals(0, $bookings->count());

        // Test query bookings in after interval
        $bookings = $innkeeper->allInRange($room, $initial_date->addDay(), $initial_date->addDays(2));
        $this->assertEquals(0, $bookings->count());
    }
}
