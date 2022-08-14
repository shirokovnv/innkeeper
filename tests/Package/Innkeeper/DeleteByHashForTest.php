<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Shirokovnv\Innkeeper\Models\Booking;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::deleteByHashFor
 */
class DeleteByHashForTest extends InnkeeperTestCase
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

        Booking::newFactory()->create(
            [
                'bookable_id' => $room->id,
                'bookable_type' => Room::class,
                'hash' => $booking_hash = Str::random(),
                'started_at' => $initial_date,
                'ended_at' => $initial_date->addHour(),
            ]
        );

        $this->assertEquals(1, $innkeeper->allFor($room)->count());

        $innkeeper->deleteByHashFor($room, $booking_hash);

        $this->assertEquals(0, $innkeeper->allFor($room)->count());
    }
}
