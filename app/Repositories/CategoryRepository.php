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
        return Category::active()->where('slug', $slug)->firstOrFail();
    }

    /**
     * Active categories with a `products_count` of shop-visible products (menus, sidebars, footer).
     *
     * @return Collection<int, Category>
     */
    public function allWithProductCounts(): Collection
    {
        return Category::active()
            ->withCount(['products' => fn ($query) => $query->visible()])
            ->get();
    }

    /**
     * @return Collection<int, Category>
     */
    public function featured(int $limit): Collection
    {
        return Category::active()->take($limit)->get();
    }
}
