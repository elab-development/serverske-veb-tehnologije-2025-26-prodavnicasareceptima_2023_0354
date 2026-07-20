<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsByCategory = [
            'Povrće' => [
                ['name' => 'Krompir', 'unit' => 'kg'],
                ['name' => 'Šargarepa', 'unit' => 'kg'],
                ['name' => 'Paradajz', 'unit' => 'kg'],
                ['name' => 'Krastavac', 'unit' => 'kg'],
                ['name' => 'Paprika', 'unit' => 'kg'],
            ],
            'Voće' => [
                ['name' => 'Jabuka', 'unit' => 'kg'],
                ['name' => 'Banana', 'unit' => 'kg'],
                ['name' => 'Pomorandža', 'unit' => 'kg'],
                ['name' => 'Limun', 'unit' => 'kg'],
                ['name' => 'Grožđe', 'unit' => 'kg'],
            ],
            'Mlečni proizvodi' => [
                ['name' => 'Mleko', 'unit' => 'l'],
                ['name' => 'Jogurt', 'unit' => 'kom'],
                ['name' => 'Sir', 'unit' => 'kg'],
                ['name' => 'Pavlaka', 'unit' => 'kom'],
                ['name' => 'Kajmak', 'unit' => 'kg'],
            ],
            'Meso' => [
                ['name' => 'Pileći file', 'unit' => 'kg'],
                ['name' => 'Juneći biftek', 'unit' => 'kg'],
                ['name' => 'Svinjski kotlet', 'unit' => 'kg'],
                ['name' => 'Mleveno meso', 'unit' => 'kg'],
                ['name' => 'Slanina', 'unit' => 'kg'],
            ],
            'Začini' => [
                ['name' => 'So', 'unit' => 'kom'],
                ['name' => 'Biber', 'unit' => 'kom'],
                ['name' => 'Origano', 'unit' => 'kom'],
                ['name' => 'Cimet', 'unit' => 'kom'],
                ['name' => 'Paprika u prahu', 'unit' => 'kom'],
            ],
            'Žitarice' => [
                ['name' => 'Pirinač', 'unit' => 'kg'],
                ['name' => 'Brašno', 'unit' => 'kg'],
                ['name' => 'Testenina', 'unit' => 'kg'],
                ['name' => 'Ovsene pahuljice', 'unit' => 'kg'],
                ['name' => 'Griz', 'unit' => 'kg'],
            ],
            'Pecivo' => [
                ['name' => 'Hleb', 'unit' => 'kom'],
                ['name' => 'Kifla', 'unit' => 'kom'],
                ['name' => 'Lepinja', 'unit' => 'kom'],
                ['name' => 'Somun', 'unit' => 'kom'],
                ['name' => 'Vekna', 'unit' => 'kom'],
            ],
            'Riba i plodovi mora' => [
                ['name' => 'Losos', 'unit' => 'kg'],
                ['name' => 'Skuša', 'unit' => 'kg'],
                ['name' => 'Oslić', 'unit' => 'kg'],
                ['name' => 'Tuna', 'unit' => 'kom'],
                ['name' => 'Škampi', 'unit' => 'kg'],
            ],
        ];

        foreach ($productsByCategory as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->first();

            foreach ($products as $productData) {
                Product::factory()->create([
                    'name' => $productData['name'],
                    'unit' => $productData['unit'],
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
