<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::factory(12)->create()->each(function (Recipe $recipe) {
            $ingredients = Product::inRandomOrder()->take(fake()->numberBetween(3, 6))->get();

            foreach ($ingredients as $ingredient) {
                $recipe->products()->attach($ingredient->id, [
                    'quantity' => fake()->randomFloat(2, 0.1, 5),
                    'unit' => fake()->randomElement(['g', 'kg', 'ml', 'l', 'kom', 'kašika', 'kašičica']),
                ]);
            }
        });
    }
}
