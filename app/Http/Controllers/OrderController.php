<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
            new Middleware('role:admin', only: ['userOrders']),
        ];
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);

        $orders = $request->user()
            ->orders()
            ->with('items.product.category')
            ->latest()
            ->paginate($perPage);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        $order->load('items.product.category');

        return new OrderResource($order);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 422);
        }

        $order = DB::transaction(function () use ($cart) {
            $order = Order::create([
                'user_id' => $cart->user_id,
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $total = 0;

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $total += $item->quantity * $item->product->price;
            }

            $order->update(['total_price' => round($total, 2)]);

            $cart->items()->delete();

            return $order;
        });

        $order->load('items.product.category');

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($order),
        ], 201);
    }

    public function userOrders(Request $request, int $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $perPage = (int) $request->input('per_page', 10);

        $orders = $user->orders()
            ->with('items.product.category')
            ->latest()
            ->paginate($perPage);

        return OrderResource::collection($orders);
    }
}
