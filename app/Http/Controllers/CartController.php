<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load('items.product.category');

        return new CartResource($cart);
    }

    public function addItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $cart = $this->getOrCreateCart($request);
        $product = Product::find($request->product_id);

        $this->addProductToCart($cart, $product, (int) $request->quantity);

        return response()->json([
            'message' => 'Product added to cart',
            'cart' => new CartResource($cart->fresh(['items.product.category'])),
        ]);
    }

    public function updateItem(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $cart = $this->getOrCreateCart($request);
        $item = $cart->items()->find($id);

        if (! $item) {
            return response()->json([
                'message' => 'Cart item not found',
            ], 404);
        }

        $item->update(['quantity' => $request->quantity]);

        return response()->json([
            'message' => 'Cart item updated',
            'cart' => new CartResource($cart->fresh(['items.product.category'])),
        ]);
    }

    public function destroyItem(Request $request, int $id)
    {
        $cart = $this->getOrCreateCart($request);
        $item = $cart->items()->find($id);

        if (! $item) {
            return response()->json([
                'message' => 'Cart item not found',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Cart item removed',
            'cart' => new CartResource($cart->fresh(['items.product.category'])),
        ]);
    }

    public function addRecipe(Request $request, Recipe $recipe)
    {
        $recipe->load('products');

        $cart = $this->getOrCreateCart($request);

        foreach ($recipe->products as $product) {
            $sameUnit = mb_strtolower(trim($product->pivot->unit)) === mb_strtolower(trim($product->unit));
            $quantity = $sameUnit ? max(1, (int) ceil($product->pivot->quantity)) : 1;

            $this->addProductToCart($cart, $product, $quantity);
        }

        return response()->json([
            'message' => 'Recipe ingredients added to cart',
            'cart' => new CartResource($cart->fresh(['items.product.category'])),
        ]);
    }

    private function getOrCreateCart(Request $request): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => 'active'],
        );
    }

    private function addProductToCart(Cart $cart, Product $product, int $quantity): CartItem
    {
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);

            return $item;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }
}
