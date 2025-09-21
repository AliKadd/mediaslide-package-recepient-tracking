<?php

namespace Database\Factories;

use App\Models\ModelProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModelProfile>
 */
class ModelProfileFactory extends Factory
{
    protected $model = ModelProfile::class;

    public function definition() {
        return [
            'name' => fake()->name(),
            'about' => $this->faker->text(100),
            'image' => $this->faker->imageUrl(640, 480, 'people'),
            'metadata' => [
                'height' => $this->faker->numberBetween(160, 190),
                'eyes' => $this->faker->randomElement(['blue', 'brown', 'green']),
                'hair' => $this->faker->randomElement(['blonde', 'black', 'brown', 'red']),
            ],
        ];
    }
}
