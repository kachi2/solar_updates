<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function findOrFail(int|string $id): Category
    {
        return Category::findOrFail($id);
    }

    public function findBySlugOrFail(string $slug): Category
    {
        return Category::where('slug', $slug)->firstOrFail();
    }

    /**
     * Categories with a `products_count` (menus, sidebars, footer).
     *
     * @return Collection<int, Category>
     */
    public function allWithProductCounts(): Collection
    {
        return Category::withCount('products')->get();
    }

    /**
     * @return Collection<int, Category>
     */
    public function featured(int $limit): Collection
    {
        return Category::take($limit)->get();
    }
}
