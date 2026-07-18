<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'total_price' => $this->whenLoaded('items', function () {
                return $this->items->sum(fn ($item) => $item->quantity * $item->product->price);
            }),
        ];
    }
}
