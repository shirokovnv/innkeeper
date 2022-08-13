<?php

declare(strict_types=1);

use Shirokovnv\Innkeeper\Tests\Room;
use Shirokovnv\Innkeeper\Tests\TestCase;

class RoomBookingTest extends TestCase
{
    /**
     * @return void
     */
    public function testRoomHasBookings(): void
    {
        /** @var Room $room */
        $room = Room::newFactory()->withBookings(10)->create();
        $this->assertEquals(10, $room->bookings()->count());

        $bookings = $room->bookings()->get();
        foreach ($bookings as $booking) {
            $this->assertEquals($room->id, $booking->bookable_id);
            $this->assertEquals(Room::class, $booking->bookable_type);
        }
    }

    /**
     * @return void
     */
    public function testRoomDoesntHaveBookings(): void
    {
        /** @var Room $room */
        $room = Room::factory()->create();
        $this->assertEquals(0, $room->bookings()->count());
    }
}
