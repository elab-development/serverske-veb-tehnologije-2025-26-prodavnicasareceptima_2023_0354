<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->inRandomOrder()->take(4)->get();

        foreach ($customers as $customer) {
            Order::factory()->create([
                'user_id' => $customer->id,
                'status' => 'completed',
                'total_price' => 0,
            ]);
        }
    }
}
