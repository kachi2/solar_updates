<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Product queries. Every shop-facing list skips products disabled in the admin (status = 1).
 */
class ProductRepository
{
    public function findOrFail(int|string $id): Product
    {
        return Product::findOrFail($id);
    }

    public function latestPaginated(int $perPage): Paginator
    {
        return Product::visible()->latest()->simplePaginate($perPage);
    }

    public function latestLengthAware(int $perPage): LengthAwarePaginator
    {
        return Product::visible()->latest()->paginate($perPage);
    }

    /**
     * @return Collection<int, Product>
     */
    public function latest(int $limit): Collection
    {
        return Product::visible()->latest()->take($limit)->get();
    }

    /**
     * @return Collection<int, Product>
     */
    public function cheapest(int $limit): Collection
    {
        return Product::visible()->orderBy('sale_price')->take($limit)->get();
    }

    public function inCategory(Category $category, int $perPage): Paginator
    {
        return Product::visible()->where('category_id', $category->id)->simplePaginate($perPage);
    }

    public function search(string $term, int $perPage): Paginator
    {
        return Product::visible()->where('name', 'like', '%'.$term.'%')->simplePaginate($perPage)->withQueryString();
    }

    /**
     * Other products in the same category.
     *
     * @return Collection<int, Product>
     */
    public function related(Product $product, int $limit): Collection
    {
        return Product::visible()->where('category_id', $product->category_id)->whereKeyNot($product->id)->take($limit)->get();
    }

    /**
     * Products by id (e.g. the session's recently-viewed list), newest first.
     *
     * @param  array<int, int|string>  $ids
     * @return Collection<int, Product>
     */
    public function findMany(array $ids, int $limit): Collection
    {
        return Product::visible()->whereIn('id', $ids)->latest()->take($limit)->get();
    }

    /**
     * Products shown in the site menus (not disabled in the admin).
     *
     * @return Collection<int, Product>
     */
    public function visible(): Collection
    {
        return Product::visible()->get();
    }

    public function recordView(Product $product): void
    {
        // `views` can be NULL in legacy rows, so not increment() (NULL + 1 = NULL in SQL).
        $product->update(['views' => (int) $product->views + 1]);
    }

    /**
     * Price for a quantity tier of a product, when one is set up.
     */
    public function variationPrice(int|string $productId, int $quantity): ?float
    {
        $price = ProductVariation::where('product_id', $productId)->where('qty', $quantity)->value('price');

        return $price === null ? null : (float) $price;
    }
}
