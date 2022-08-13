<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Package\Innkeeper;

use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Shirokovnv\Innkeeper\Constants;
use Shirokovnv\Innkeeper\Exceptions\WrongDateInterval;
use Shirokovnv\Innkeeper\Models\Booking;
use Shirokovnv\Innkeeper\Tests\Room;

/**
 * @covers \Shirokovnv\Innkeeper\Innkeeper::book
 */
class BookTest extends InnkeeperTestCase
{
    /**
     * @return void
     */
    public function testSingleBooking(): void
    {
        $innkeeper = $this->getInnkeeper();
        /** @var Room $room */
        $room = Room::newFactory()->create();

        $started_at = Carbon::now()->toImmutable();
        $ended_at = $started_at->addHour()->toImmutable();
        $booking_hash = Str::random();

        $booking = $innkeeper->book($room, $booking_hash, $started_at, $ended_at);
        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals($room->id, $booking->bookable_id);
        $this->assertEquals(Room::class, $booking->bookable_type);
        $this->assertEquals($started_at->format(Constants::MYSQL_DATE_FORMAT), $booking->started_at);
        $this->assertEquals($ended_at->format(Constants::MYSQL_DATE_FORMAT), $booking->ended_at);
    }

    /**
     * @return void
     */
    public function testCannotBookWithTheSameHash(): void
    {
        $innkeeper = $this->getInnkeeper();
        /** @var Room $room */
        $room = Room::newFactory()->create();

        $started_at = Carbon::now()->toImmutable();
        $ended_at = $started_at->addHour()->toImmutable();
        $booking_hash = Str::random();

        $this->expectException(QueryException::class);

        $innkeeper->book($room, $booking_hash, $started_at, $ended_at);
        $innkeeper->book($room, $booking_hash, $started_at->addDay(), $ended_at->addDay());
    }

    /**
     * @return void
     */
    public function testWrongDateInterval(): void
    {
        $innkeeper = $this->getInnkeeper();
        /** @var Room $room */
        $room = Room::newFactory()->create();

        $initial_date = Carbon::now()->toImmutable();
        $booking_hash = Str::random();

        $this->expectException(WrongDateInterval::class);

        $innkeeper->book($room, $booking_hash, $initial_date, $initial_date->subHour());
    }
}
