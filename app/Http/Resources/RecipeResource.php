<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RecipeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'instructions' => $this->instructions,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'prep_time' => $this->prep_time,
            'ingredients' => $this->whenLoaded('products', function () use ($request) {
                return $this->products->map(function ($product) use ($request) {
                    return array_merge(
                        (new ProductResource($product))->resolve($request),
                        [
                            'quantity' => $product->pivot->quantity,
                            'unit' => $product->pivot->unit,
                        ]
                    );
                });
            }),
        ];
    }
}
