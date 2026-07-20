<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum', 'role:admin']),
        ];
    }

    public function topProducts(Request $request)
    {
        $limit = (int) $request->input('limit', 10);

        $products = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'product_id' => $row->product_id,
                'product_name' => $row->product_name,
                'total_sold' => (int) $row->total_sold,
                'total_revenue' => round((float) $row->total_revenue, 2),
            ]);

        return response()->json([
            'data' => $products,
        ]);
    }

    public function revenueByCategory()
    {
        $categories = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select(
                'categories.id as category_id',
                'categories.name as category_name',
                DB::raw('SUM(order_items.price * order_items.quantity) as revenue'),
                DB::raw('SUM(order_items.quantity) as items_sold'),
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn ($row) => [
                'category_id' => $row->category_id,
                'category_name' => $row->category_name,
                'revenue' => round((float) $row->revenue, 2),
                'items_sold' => (int) $row->items_sold,
            ]);

        return response()->json([
            'data' => $categories,
        ]);
    }
}
