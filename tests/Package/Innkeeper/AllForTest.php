<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Shirokovnv\Innkeeper\Models\Booking;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::allFor
 */
class AllForTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testEmptyCollection(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $bookings = $innkeeper->allFor($room);
        $this->assertEquals(0, $bookings->count());
    }

    /**
     * @return void
     */
    public function testNonEmptyCollection(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->withBookings($count_bookings = 10)->create();

        $bookings = $innkeeper->allFor($room);
        $this->assertEquals($count_bookings, $bookings->count());
        foreach ($bookings as $booking) {
            $this->assertInstanceOf(Booking::class, $booking);
        }
    }
}
