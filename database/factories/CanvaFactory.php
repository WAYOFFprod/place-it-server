<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CanvaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'category' => 'pixelwar',
            'access' => 'open',
            'visibility' => 'public',
            'width' => 32,
            'height' => 32,
            'colors' => [
                "#ffd887",
                "#eb9361",
                "#da5e4e",
                "#ab2330",
                "#dfffff",
                "#b5de89",
                "#6aab7c",
                "#26616b",
                "#a2dceb",
                "#759ed0",
                "#434ea8",
                "#2a2140",
                "#e1a7c5",
                "#ab7ac6",
                "#735bab",
                "#3b3772"
            ]
        ];
    }
}
