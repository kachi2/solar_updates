{{--
    Sidebar category card (products listing, category pages, product page).
    Usage: @include('includes.category-card', ['activeCategoryId' => $id|null, 'allActive' => bool])
--}}
<nav class="sofar-pd-cats" aria-label="Product categories">
    <h2 class="sofar-pd-cats__title"><i class="fa fa-list-ul" aria-hidden="true"></i> Categories</h2>
    <ul class="sofar-pd-cats__list">
        <li>
            <a href="{{ route('pages', 'products') }}" @class(['sofar-pd-cats__link', 'is-active' => $allActive ?? false])>
                <span>All products</span>
                <span class="sofar-pd-cats__count">{{ $menu_categories->sum('products_count') }}</span>
            </a>
        </li>
        @foreach ($menu_categories as $menuCategory)
            <li>
                <a href="{{ route('user.category', $menuCategory) }}" @class(['sofar-pd-cats__link', 'is-active' => $menuCategory->id === ($activeCategoryId ?? null)])>
                    <span>{{ $menuCategory->name }}</span>
                    <span class="sofar-pd-cats__count">{{ $menuCategory->products_count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
