<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Shirokovnv\Innkeeper\Models\Booking;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::all
 */
class AllTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testGetAllTheBookings(): void
    {
        Booking::newFactory()
            ->count($count_bookings = 10)
            ->create();

        $innkeeper = $this->getInnkeeper();
        $bookings = $innkeeper->all();
        $this->assertEquals($count_bookings, $bookings->count());
        foreach ($bookings as $booking) {
            $this->assertInstanceOf(Booking::class, $booking);
        }
    }
}
