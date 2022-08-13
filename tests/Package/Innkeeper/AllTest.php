<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Shirokovnv\Innkeeper\Models\Booking;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::all
 */
class AllTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testEmptyCollection(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $booking_collection = $innkeeper->all($room);
        $this->assertEquals(0, $booking_collection->count());
    }

    /**
     * @return void
     */
    public function testNonEmptyCollection(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->withBookings($count_bookings = 10)->create();

        $booking_collection = $innkeeper->all($room);
        $this->assertEquals($count_bookings, $booking_collection->count());
        foreach ($booking_collection as $booking) {
            $this->assertInstanceOf(Booking::class, $booking);
        }
    }
}
