<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InkubisStage>
 */
class InkubisStageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahapan_inkubasi' => $this->faker->word(),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}
