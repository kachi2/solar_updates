@extends('layouts.app')
@section('title', 'Page not found · ')
@section('content')
<main id="content" role="main" class="sofar-error">
    <div class="container">
        <section class="sofar-error__box">
            <p class="sofar-error__code" aria-hidden="true">404</p>
            <h1 class="sofar-error__title">We can't find that page</h1>
            <p class="sofar-error__text">The link may be old, or the page may have moved. Try a search, or pick up from one of the links below.</p>

            <form action="{{ route('search') }}" method="GET" class="sofar-error__search" role="search">
                <label for="errorSearch" class="sr-only">Search products</label>
                <input type="search" id="errorSearch" name="search" placeholder="Search inverters, batteries, panels…" required>
                <button type="submit" class="sofar-cart-btn">Search</button>
            </form>

            <div class="sofar-error__actions">
                <a href="{{ route('index') }}" class="sofar-cart-btn">Go to homepage</a>
                <a href="{{ route('pages', 'products') }}" class="sofar-checkout-step__secondary sofar-account__browse">Browse products</a>
            </div>

            @if (isset($menu_categories) && $menu_categories->isNotEmpty())
                <div class="sofar-error__links">
                    <span>Popular categories</span>
                    <ul>
                        @foreach ($menu_categories as $errorCategory)
                            <li><a href="{{ route('user.category', $errorCategory) }}">{{ $errorCategory->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>
    </div>
</main>
@endsection
