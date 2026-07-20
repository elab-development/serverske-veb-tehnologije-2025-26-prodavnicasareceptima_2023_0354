<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SpoonacularService
{
    public function findByIngredients(array $ingredients, int $number = 10): array
    {
        $normalized = collect($ingredients)
            ->map(fn ($ingredient) => mb_strtolower(trim($ingredient)))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->implode(',');

        $cacheKey = 'spoonacular:find-by-ingredients:'.md5($normalized.'|'.$number);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($normalized, $number) {
            $response = Http::get(rtrim(config('services.spoonacular.base_url'), '/').'/recipes/findByIngredients', [
                'apiKey' => config('services.spoonacular.key'),
                'ingredients' => $normalized,
                'number' => $number,
                'ranking' => 1,
                'ignorePantry' => true,
            ]);

            $response->throw();

            return $response->json();
        });
    }
}
