<?php

namespace Database\Factories;

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
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(),
            'status' => fake()->word(),
            'region' => fake()->word(),
            'venue_id' => Venue::factory(),
        ];
    }
}
