<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CameraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'traffic_direction' => $this->faker->randomElement(['inbound', 'outbound']),
            // x1, y1 3.5419187458628683, 103.42440718917268
            // x2, y1 3.548472228191322, 103.43470687181146
            // x1, y2 3.5343800421528715, 103.43256110453628
            // x2, y2 3.542818246161377, 103.4359514167738
            'latitude' => $this->faker->latitude($min = 3.534, $max = 3.548),
            'longitude' => $this->faker->longitude($min = 103.424, $max = 103.435),
        ];
    }
}
