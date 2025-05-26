<?php

namespace Database\Factories;

use App\Enums\Region;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Conference;
use App\Models\Venue;

class ConferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Conference::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        //$startDate = $this->faker->dateTimeBetween('-1 year', '+1 year');
        $startDate = now()->addMonth(9);
        $endDate = now()->addMonth(9)->addDays(rand(1, 10));
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(Status::class),
            'region' => $this->faker->randomElement(Region::class),
            //'venue_id' => null, // Set to null for now, can be updated later
            'venue_id' => Venue::factory(),
        ];
    }
}
