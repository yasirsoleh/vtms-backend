<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Camera;

class DetectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'camera_id' => Camera::all()->except(['1','2'])->random()->id,
            'plate_number' => $this->faker->stateAbbr().$this->faker->buildingNumber(),
        ];
    }
}
