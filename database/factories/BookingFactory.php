<?php

declare(strict_types=1);

namespace Shirokovnv\Innkeeper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shirokovnv\Innkeeper\Models\Booking;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hash' => $this->faker->uuid(),
            'bookable_id' => $this->faker->randomNumber(),
            'bookable_type' => $this->faker->word(),
            'started_at' => $this->faker->dateTime(),
            'ended_at' => $this->faker->dateTime(),
        ];
    }
}
