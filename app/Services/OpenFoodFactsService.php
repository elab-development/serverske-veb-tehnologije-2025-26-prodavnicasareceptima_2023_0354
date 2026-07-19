<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenFoodFactsService
{
    public function searchByName(string $name): ?array
    {
        $cacheKey = 'openfoodfacts:search:'.md5(mb_strtolower(trim($name)));

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($name) {
            try {
                $response = Http::timeout(5)
                    ->withHeaders(['User-Agent' => 'RecipeShopApp/1.0 (student project)'])
                    ->get('https://world.openfoodfacts.org/cgi/search.pl', [
                        'search_terms' => $name,
                        'search_simple' => 1,
                        'action' => 'process',
                        'json' => 1,
                        'page_size' => 1,
                        'fields' => 'product_name,nutriments',
                    ]);
            } catch (ConnectionException) {
                return null;
            }

            if (! $response->successful()) {
                return null;
            }

            $products = $response->json('products');

            if (! is_array($products) || empty($products)) {
                return null;
            }

            $nutriments = $products[0]['nutriments'] ?? [];

            return [
                'product_name' => $products[0]['product_name'] ?? null,
                'calories_kcal_per_100g' => $nutriments['energy-kcal_100g'] ?? null,
                'proteins_g_per_100g' => $nutriments['proteins_100g'] ?? null,
                'fat_g_per_100g' => $nutriments['fat_100g'] ?? null,
                'carbohydrates_g_per_100g' => $nutriments['carbohydrates_100g'] ?? null,
            ];
        });
    }
}
