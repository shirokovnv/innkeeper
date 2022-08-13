<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::first
 */
class FirstTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testGetFirstBooking(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $initial_date = Carbon::now()->toImmutable();
        $this->createBookingsForRoom($room->id, $initial_date, 1, 5);

        $first_booking = $innkeeper->first($room);
        $this->assertEquals($initial_date->format('Y-m-d H:i:s'), $first_booking->started_at);
    }
}
