<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Models\Booking;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::allInRange
 */
class AllInRangeTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testGetBookingsInRange(): void
    {
        $initial_date = Carbon::now()->toImmutable();

        $count_bookings = 10;

        for ($i = 0; $i < $count_bookings; $i++) {
            $start_date = $initial_date->addHour($i);

            Booking::newFactory()
                ->create(
                    [
                        'started_at' => $start_date,
                        'ended_at' => $start_date->addHour(),
                    ]
                );
        }

        $innkeeper = $this->getInnkeeper();

        // Test query bookings in exact interval
        $bookings = $innkeeper->allInRange($initial_date, $initial_date->addHours($count_bookings));

        $this->assertEquals($count_bookings, $bookings->count());
        foreach ($bookings as $booking) {
            $this->assertInstanceOf(Booking::class, $booking);
        }

        // Test query bookings in partial interval
        $bookings = $innkeeper->allInRange($initial_date, $initial_date->addHour());
        $this->assertEquals(1, $bookings->count());

        // Test query bookings in before interval
        $bookings = $innkeeper->allInRange($initial_date->subDay(), $initial_date);
        $this->assertEquals(0, $bookings->count());

        // Test query bookings in after interval
        $bookings = $innkeeper->allInRange($initial_date->addDay(), $initial_date->addDays(2));
        $this->assertEquals(0, $bookings->count());
    }
}
