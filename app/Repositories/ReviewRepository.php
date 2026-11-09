<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository
{
    /**
     * @return Collection<int, Review>
     */
    public function latestFor(Product $product, int $limit): Collection
    {
        return Review::where('product_id', $product->id)->latest()->take($limit)->get();
    }

    /**
     * @return array{count: int, average: float}
     */
    public function summaryFor(Product $product): array
    {
        $row = Review::where('product_id', $product->id)
            ->selectRaw('COUNT(*) as review_count, AVG(rating) as average_rating')
            ->first();

        return [
            'count' => (int) ($row->review_count ?? 0),
            'average' => round((float) ($row->average_rating ?? 0), 1),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Review
    {
        return Review::create($attributes);
    }
}
