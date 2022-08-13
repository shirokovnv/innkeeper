<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::last
 */
class LastTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testGetLast(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $initial_date = Carbon::now()->toImmutable();
        $this->createBookingsForRoom($room->id, $initial_date, 1, 2);

        $last_booking = $innkeeper->last($room);
        $this->assertEquals($initial_date->addHours(2)->format('Y-m-d H:i:s'), $last_booking->ended_at);
    }
}
