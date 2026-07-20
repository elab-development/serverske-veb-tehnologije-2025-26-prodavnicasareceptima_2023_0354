<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = Cart::all();

        foreach ($carts as $cart) {
            $products = Product::inRandomOrder()->take(fake()->numberBetween(1, 3))->get();

            foreach ($products as $product) {
                CartItem::factory()->create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => fake()->numberBetween(1, 5),
                ]);
            }
        }
    }
}
