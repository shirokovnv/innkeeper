<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::canBook
 */
class CanBookTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testBookingIsAllowed(): void
    {
        $innkeeper = $this->getInnkeeper();

        $initial_date = Carbon::now()->toImmutable();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $this->assertTrue($innkeeper->canBook($room, $initial_date, $initial_date->addHour()));

        $this->createBookingsForRoom($room->id, $initial_date, 1, 2, 1);

        $this->assertTrue($innkeeper->canBook($room, $initial_date->addHour(), $initial_date->addHours(2)));
        $this->assertTrue($innkeeper->canBook($room, $initial_date->subHour(), $initial_date));
        $this->assertTrue($innkeeper->canBook($room, $initial_date->addHours(3), $initial_date->addHours(4)));
    }

    /**
     * @return void
     */
    public function testBookingIsDisallowed(): void
    {
        $innkeeper = $this->getInnkeeper();

        $initial_date = Carbon::now()->toImmutable();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $this->createBookingsForRoom($room->id, $initial_date, 1, 2);

        $this->assertFalse($innkeeper->canBook($room, $initial_date, $initial_date->addHour()));
        $this->assertFalse($innkeeper->canBook($room, $initial_date->subHour(), $initial_date->addMinutes(30)));
        $this->assertFalse($innkeeper->canBook($room, $initial_date->addMinutes(90), $initial_date->addHours(3)));
        $this->assertFalse($innkeeper->canBook($room, $initial_date->subHour(), $initial_date->addHours(3)));
    }
}
