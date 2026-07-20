<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->inRandomOrder()->take(3)->get();

        foreach ($customers as $customer) {
            Cart::factory()->create([
                'user_id' => $customer->id,
                'status' => 'active',
            ]);
        }
    }
}
