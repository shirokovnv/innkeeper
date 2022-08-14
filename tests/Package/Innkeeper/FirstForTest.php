<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Constants;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::firstFor
 */
class FirstForTest extends InnkeeperTestCase
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

        $first_booking = $innkeeper->firstFor($room);
        $this->assertEquals($initial_date->format(Constants::MYSQL_DATE_FORMAT), $first_booking->started_at);
    }
}
