<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shirokovnv\Innkeeper\Models\Booking;
use Shirokovnv\Innkeeper\Tests\Room;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }

    /**
     * Create room with random bookings.
     *
     * @param int $count_bookings
     * @param \DateTimeInterface|null $started_at
     * @param \DateTimeInterface|null $ended_at
     * @return RoomFactory
     */
    public function withBookings(
        int $count_bookings,
        ?\DateTimeInterface $started_at = null,
        ?\DateTimeInterface $ended_at = null
    ): self {
        return $this->state(function (array $attributes) {
            return [];
        })->afterCreating(function (Room $room) use ($count_bookings, $started_at, $ended_at) {
            Booking::factory()->count($count_bookings)->create(
                [
                    'bookable_id' => $room->id,
                    'bookable_type' => Room::class,
                    'started_at' => $started_at ?? $this->faker->dateTime(),
                    'ended_at' => $ended_at ?? $this->faker->dateTime(),
                ]
            );
        });
    }
}
