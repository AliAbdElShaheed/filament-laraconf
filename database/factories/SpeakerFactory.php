<?php

namespace Database\Factories;

use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Speaker;

class SpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $qualificationsCount = $this->faker->numberBetween(1, 5);
        $qualifications = $this->faker->randomElements(
            Speaker::QUALIFICATIONS,
            $qualificationsCount
        );
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'qualifications' => $qualifications,
            'bio' => fake()->text(),
            'twitter_handle' => fake()->word(),
        ];
    }
    
    public function withTalks(int $count = 1): self
    {
        return $this->has(Talk::factory()->count($count), 'talks');
    }

}
