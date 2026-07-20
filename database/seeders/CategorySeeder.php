<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Povrće',
            'Voće',
            'Mlečni proizvodi',
            'Meso',
            'Začini',
            'Žitarice',
            'Pecivo',
            'Riba i plodovi mora',
        ];

        foreach ($categories as $name) {
            Category::factory()->create(['name' => $name]);
        }
    }
}
