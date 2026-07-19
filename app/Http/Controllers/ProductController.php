<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\OpenFoodFactsService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum', 'role:admin'], only: ['store', 'update', 'destroy', 'uploadImage']),
        ];
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('search')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($request->search).'%']);
        }

        $perPage = (int) $request->input('per_page', 10);

        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load('category');

        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'name')->where('category_id', $request->category_id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Product::create($validator->validated());
        $product->load('category');

        return response()->json([
            'message' => 'Product created successfully',
            'product' => new ProductResource($product),
        ], 201);
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'name')
                    ->where('category_id', $request->category_id)
                    ->ignore($product->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $product->update($validator->validated());
        $product->load('category');

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => new ProductResource($product),
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }

    public function uploadImage(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $path = $request->file('image')->store('products', 'public');

        $product->update(['image' => $path]);
        $product->load('category');

        return response()->json([
            'message' => 'Product image uploaded successfully',
            'product' => new ProductResource($product),
        ]);
    }

    public function nutritionInfo(Product $product, OpenFoodFactsService $openFoodFacts)
    {
        $info = $openFoodFacts->searchByName($product->name);

        if (! $info) {
            return response()->json([
                'message' => 'Nutrition data not available for this product',
            ]);
        }

        return response()->json([
            'data' => $info,
        ]);
    }
}
