<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpoonacularService
{
    public function findByIngredients(array $ingredients, int $number = 10): array
    {
        $response = Http::get(rtrim(config('services.spoonacular.base_url'), '/').'/recipes/findByIngredients', [
            'apiKey' => config('services.spoonacular.key'),
            'ingredients' => implode(',', $ingredients),
            'number' => $number,
            'ranking' => 1,
            'ignorePantry' => true,
        ]);

        $response->throw();

        return $response->json();
    }
}
