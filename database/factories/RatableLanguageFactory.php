<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RatableLanguage>
 */
class RatableLanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'language' => 'en',
            'name' => fake()->word(),
            'discription' => fake()->paragraph()
        ];
    }

    public function dutch()
    {
        return $this->state(function () {
            return [
                'language' => 'nl',
            ];
        });
    }
}
