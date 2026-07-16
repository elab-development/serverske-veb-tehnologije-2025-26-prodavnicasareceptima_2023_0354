<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'instructions',
        'image',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'recipe_product')
            ->withPivot(['quantity', 'unit']);
    }
}
