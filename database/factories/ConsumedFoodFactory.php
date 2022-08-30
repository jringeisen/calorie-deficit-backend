<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsumedFood>
 */
class ConsumedFoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $servings = collect([1,2,3])->random();
        $calories = collect([80, 100, 200])->random();
        $total_calories = $calories * $servings;

        return [
            'user_id' => User::factory()->create(),
            'servings' => $servings,
            'name' => collect(['Pineapple', 'Orange', 'Egg', 'Chicken'])->random(),
            'calories' => collect([80, 100, 200])->random(),
            'total_calories' => $total_calories,
        ];
    }
}
