<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Color>
 */
class ColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public static function generateRandomColorCode($faker)
    {

        $letters = '';
        for ($i = 0; $i < 5; $i++) {
            $letters .= $faker->randomLetter();
        }

        return '#'.$letters.$faker->randomNumber(1);

    }

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->colorName(),
            'code' => self::generateRandomColorCode($this->faker),
        ];
    }
}
