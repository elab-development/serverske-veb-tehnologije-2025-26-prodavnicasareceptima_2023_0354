<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum', 'role:admin'], only: ['store', 'update', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);

        $recipes = Recipe::with('products')->paginate($perPage);

        return RecipeResource::collection($recipes);
    }

    public function show(Recipe $recipe)
    {
        $recipe->load('products');

        return new RecipeResource($recipe);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'instructions' => ['required', 'string'],
            'prep_time' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $recipe = Recipe::create($validator->validated());

        return response()->json([
            'message' => 'Recipe created successfully',
            'recipe' => new RecipeResource($recipe),
        ], 201);
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'instructions' => ['required', 'string'],
            'prep_time' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $recipe->update($validator->validated());
        $recipe->load('products');

        return response()->json([
            'message' => 'Recipe updated successfully',
            'recipe' => new RecipeResource($recipe),
        ]);
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return response()->json([
            'message' => 'Recipe deleted successfully',
        ]);
    }

    public function ingredients(Recipe $recipe)
    {
        $recipe->load('products');

        $ingredients = $recipe->products->map(function ($product) {
            return array_merge(
                (new ProductResource($product))->resolve(request()),
                [
                    'quantity' => $product->pivot->quantity,
                    'unit' => $product->pivot->unit,
                ]
            );
        });

        return response()->json([
            'data' => $ingredients,
        ]);
    }
}
