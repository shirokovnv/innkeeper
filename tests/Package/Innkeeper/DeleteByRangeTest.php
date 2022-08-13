<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::deleteByRange
 */
class DeleteByRangeTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testDeleted(): void
    {
        $innkeeper = $this->getInnkeeper();

        /** @var Room $room */
        $room = Room::newFactory()->create();

        $initial_date = Carbon::now()->toImmutable();
        $this->createBookingsForRoom($room->id, $initial_date, 1, 1);

        $this->assertEquals(1, $innkeeper->all($room)->count());

        $innkeeper->deleteByRange($room, $initial_date, $initial_date->addHour());

        $this->assertEquals(0, $innkeeper->all($room)->count());
    }
}
