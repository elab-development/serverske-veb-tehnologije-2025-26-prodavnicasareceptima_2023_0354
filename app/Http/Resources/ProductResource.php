<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
