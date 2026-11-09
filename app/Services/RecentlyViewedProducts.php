<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * The visitor's recently viewed products, kept in the session.
 */
class RecentlyViewedProducts
{
    private const SESSION_KEY = 'products.recently_viewed';

    public function __construct(private readonly ProductRepository $products) {}

    public function remember(Product $product): void
    {
        session()->push(self::SESSION_KEY, $product->getKey());
    }

    /**
     * @return Collection<int, Product>
     */
    public function latest(int $limit): Collection
    {
        $ids = session(self::SESSION_KEY);

        if (! is_array($ids) || $ids === []) {
            return new Collection;
        }

        return $this->products->findMany(array_slice(array_unique($ids), -$limit, $limit), $limit);
    }
}
