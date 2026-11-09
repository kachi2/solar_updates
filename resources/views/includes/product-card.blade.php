{{--
    Product card used by every product grid.
    Usage: @include('includes.product-card', ['product' => $pro])
    Pass 'quickAdd' => false to make "Add to Cart" open the product page instead
    of adding the item directly (used on the home page).
--}}
@php
    $quickAdd = $quickAdd ?? true;
    $detailsUrl = route('product-details', $product);
    $price = (float) $product->sale_price;
    $oldPrice = (float) $product->price;
    $discount = $oldPrice > $price && $oldPrice > 0 ? (int) round(($oldPrice - $price) / $oldPrice * 100) : 0;
    $whatsappText = "Hello Sofar Solar, I'd like to order:\n"
        .$product->name."\n"
        .'Price: ₦'.number_format($price);
    $whatsappUrl = 'https://wa.me/'.config('services.whatsapp.number').'?text='.rawurlencode($whatsappText);
@endphp
<article class="sofar-product-card">
    <a href="{{ $detailsUrl }}" class="sofar-product-card__media" aria-label="{{ $product->name }}">
        @if ($discount > 0)
            <span class="sofar-product-card__badge">-{{ $discount }}%</span>
        @endif
        <img class="sofar-product-card__img" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
    </a>

    <div class="sofar-product-card__body">
        @if ($product->category)
            <span class="sofar-product-card__category">{{ $product->category->name }}</span>
        @endif

        <h3 class="sofar-product-card__title">
            <a href="{{ $detailsUrl }}">{{ $product->name }}</a>
        </h3>

        <div class="sofar-product-card__price">
            <span class="sofar-product-card__now">₦{{ number_format($price) }}</span>
            @if ($discount > 0)
                <del class="sofar-product-card__was">₦{{ number_format($oldPrice) }}</del>
            @endif
        </div>
        @if ((float) $product->exchange_rate > 0)
            <span class="sofar-product-card__usd">≈ {{ number_format((float) $product->exchange_rate) }} USD</span>
        @endif

        <div class="sofar-product-card__actions">
            @if ($quickAdd)
                <button type="button" class="sofar-product-card__btn sofar-product-card__btn--cart js-quick-add" data-url="{{ route('carts.add', ['id' => $product->id]) }}">
                    Add to Cart <i class="ec ec-add-to-cart" aria-hidden="true"></i>
                </button>
            @else
                <a href="{{ $detailsUrl }}" class="sofar-product-card__btn sofar-product-card__btn--cart">
                    Add to Cart <i class="ec ec-add-to-cart" aria-hidden="true"></i>
                </a>
            @endif
            <a href="{{ $whatsappUrl }}" class="sofar-product-card__btn sofar-product-card__btn--whatsapp" target="_blank" rel="noopener">
                <i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp
            </a>
        </div>
    </div>
</article>
