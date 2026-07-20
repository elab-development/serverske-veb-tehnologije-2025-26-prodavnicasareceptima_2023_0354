<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Sensible ingredients per curated recipe name, using only products
     * that exist in ProductSeeder. Covers all recipe names available in
     * RecipeFactory, since Recipe::factory(12) picks a random 12 of them.
     *
     * @var array<string, array<int, array{product: string, quantity: float, unit: string}>>
     */
    protected static array $ingredientsByRecipe = [
        'Sarma' => [
            ['product' => 'Mleveno meso', 'quantity' => 0.5, 'unit' => 'kg'],
            ['product' => 'Pirinač', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Paprika u prahu', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'Biber', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Musaka' => [
            ['product' => 'Krompir', 'quantity' => 1, 'unit' => 'kg'],
            ['product' => 'Mleveno meso', 'quantity' => 0.5, 'unit' => 'kg'],
            ['product' => 'Mleko', 'quantity' => 0.3, 'unit' => 'l'],
            ['product' => 'Paradajz', 'quantity' => 0.3, 'unit' => 'kg'],
        ],
        'Pasulj' => [
            ['product' => 'Svinjski kotlet', 'quantity' => 0.3, 'unit' => 'kg'],
            ['product' => 'Šargarepa', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Paprika', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Paprika u prahu', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Karađorđeva šnicla' => [
            ['product' => 'Svinjski kotlet', 'quantity' => 0.6, 'unit' => 'kg'],
            ['product' => 'Kajmak', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Brašno', 'quantity' => 0.1, 'unit' => 'kg'],
        ],
        'Đuveč' => [
            ['product' => 'Paprika', 'quantity' => 0.3, 'unit' => 'kg'],
            ['product' => 'Paradajz', 'quantity' => 0.4, 'unit' => 'kg'],
            ['product' => 'Pirinač', 'quantity' => 0.25, 'unit' => 'kg'],
            ['product' => 'Krastavac', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Origano', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Punjene paprike' => [
            ['product' => 'Paprika', 'quantity' => 1, 'unit' => 'kg'],
            ['product' => 'Mleveno meso', 'quantity' => 0.5, 'unit' => 'kg'],
            ['product' => 'Pirinač', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Paradajz', 'quantity' => 0.3, 'unit' => 'kg'],
        ],
        'Riblja čorba' => [
            ['product' => 'Oslić', 'quantity' => 0.6, 'unit' => 'kg'],
            ['product' => 'Šargarepa', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Paprika u prahu', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Palačinke' => [
            ['product' => 'Brašno', 'quantity' => 0.25, 'unit' => 'kg'],
            ['product' => 'Mleko', 'quantity' => 0.5, 'unit' => 'l'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Pileća supa' => [
            ['product' => 'Pileći file', 'quantity' => 0.5, 'unit' => 'kg'],
            ['product' => 'Šargarepa', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Testenina', 'quantity' => 0.1, 'unit' => 'kg'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Gulaš' => [
            ['product' => 'Juneći biftek', 'quantity' => 0.6, 'unit' => 'kg'],
            ['product' => 'Paprika u prahu', 'quantity' => 1, 'unit' => 'kašika'],
            ['product' => 'Paradajz', 'quantity' => 0.3, 'unit' => 'kg'],
            ['product' => 'Šargarepa', 'quantity' => 0.2, 'unit' => 'kg'],
        ],
        'Ćevapi' => [
            ['product' => 'Mleveno meso', 'quantity' => 0.7, 'unit' => 'kg'],
            ['product' => 'Biber', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'Paprika u prahu', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Pljeskavica' => [
            ['product' => 'Mleveno meso', 'quantity' => 0.6, 'unit' => 'kg'],
            ['product' => 'Biber', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Ajvar' => [
            ['product' => 'Paprika', 'quantity' => 1.5, 'unit' => 'kg'],
            ['product' => 'Biber', 'quantity' => 1, 'unit' => 'kašičica'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Kupus salata' => [
            ['product' => 'Krastavac', 'quantity' => 0.3, 'unit' => 'kg'],
            ['product' => 'Šargarepa', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'So', 'quantity' => 1, 'unit' => 'kašičica'],
        ],
        'Šopska salata' => [
            ['product' => 'Paradajz', 'quantity' => 0.3, 'unit' => 'kg'],
            ['product' => 'Krastavac', 'quantity' => 0.3, 'unit' => 'kg'],
            ['product' => 'Paprika', 'quantity' => 0.2, 'unit' => 'kg'],
            ['product' => 'Sir', 'quantity' => 0.1, 'unit' => 'kg'],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::factory(12)->create()->each(function (Recipe $recipe) {
            $ingredients = static::$ingredientsByRecipe[$recipe->name] ?? [];

            foreach ($ingredients as $ingredient) {
                $product = Product::where('name', $ingredient['product'])->first();

                if (! $product) {
                    continue;
                }

                $recipe->products()->attach($product->id, [
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                ]);
            }
        });
    }
}
