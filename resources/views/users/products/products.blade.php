@extends('layouts.app')
@use('App\Support\Media')
@section('title', $product->name)
@section('description', $product->name.((float) $product->sale_price > 0 ? ' for ₦'.number_format((float) $product->sale_price) : '').' at Sofar Solar Nigeria. '.((string) $product->description ?: 'Order online or on WhatsApp with delivery across Nigeria.'))
@section('image', (string) $product->image_url)
@section('og_type', 'product')
@push('head')
@php
    // Google product data (rich results: price, stock, star rating).
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => array_values(array_filter([$product->image_url])),
        'description' => \Illuminate\Support\Str::limit(trim(preg_replace('/[\s\x{00A0}]+/u', ' ', html_entity_decode(preg_replace('/<[^>]*>/', ' ', (string) $product->description), ENT_QUOTES | ENT_HTML5))), 500),
        'sku' => (string) $product->id,
        'url' => route('product-details', $product),
    ];
    if ((float) $product->sale_price > 0) {
        $productSchema['offers'] = [
            '@type' => 'Offer',
            'url' => route('product-details', $product),
            'priceCurrency' => 'NGN',
            'price' => number_format((float) $product->sale_price, 2, '.', ''),
            'availability' => 'https://schema.org/InStock',
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller' => ['@type' => 'Organization', 'name' => 'Sofar Solar'],
        ];
    }
    if ($reviewCount > 0) {
        $productSchema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => round((float) $averageRating, 1),
            'reviewCount' => $reviewCount,
        ];
    }
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
@section('content')
@php
    // Main image first, then gallery images (no duplicates / empties).
    $images = collect([$product->image])
        ->merge(json_decode((string) $product->gallery, true) ?: [])
        ->filter()
        ->unique()
        ->values();
    $price = (float) $product->sale_price;
    $oldPrice = (float) $product->price;
    $discount = $oldPrice > $price && $oldPrice > 0 ? (int) round(($oldPrice - $price) / $oldPrice * 100) : 0;
    $roundedRating = (int) round($averageRating);
    $whatsappText = "Hello Sofar Solar, I'd like to order:\n".$product->name."\nPrice: ₦".number_format($price);
    $whatsappUrl = 'https://wa.me/'.config('services.whatsapp.number').'?text='.rawurlencode($whatsappText);
@endphp
<main id="content" role="main" class="sofar-pd">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="sofar-pd__breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                @if ($product->category)
                    <li class="breadcrumb-item"><a href="{{ route('user.category', $product->category) }}">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Sidebar: categories (open by default) -->
            <aside class="d-none d-xl-block col-xl-3">
                @include('includes.category-card', ['activeCategoryId' => $product->category_id])
            </aside>

            <div class="col-xl-9">
                <!-- Product summary -->
                <section class="sofar-pd-summary">
                    <div class="row">
                        <!-- Gallery -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="sofar-pd-gallery">
                                <div class="sofar-pd-gallery__main">
                                   
                                    <img id="sofarPdMainImage" src="{{ Media::sized(Media::PRODUCTS, $images->first(), 480) }}" alt="{{ $product->name }}">
                                </div>
                            </div>
                            <!-- Tabs -->
                            <section class="sofar-pd-tabs sofar-pd-tabs--inline" id="pdTabs">
                                <ul class="nav sofar-pd-tabs__nav" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pd-description-tab" data-toggle="pill" href="#pd-description" role="tab" aria-controls="pd-description" aria-selected="true">Description</a>
                                    </li>
                                   
                                    <li class="nav-item">
                                        <a class="nav-link" id="pd-reviews-tab" data-toggle="pill" href="#pd-reviews" role="tab" aria-controls="pd-reviews" aria-selected="false">Reviews <span class="sofar-pd-tabs__count">{{ number_format($reviewCount) }}</span></a>
                                    </li>
                                </ul>

                                <div class="tab-content sofar-pd-tabs__panel">
                                    <div class="tab-pane fade show active sofar-pd-description" id="pd-description" role="tabpanel" aria-labelledby="pd-description-tab">
                                        {!! $product->description !!}
                                    </div>

                                    

                                    <div class="tab-pane fade" id="pd-reviews" role="tabpanel" aria-labelledby="pd-reviews-tab">
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <h3 class="sofar-pd-tabs__heading">Customer reviews</h3>
                                                @forelse ($rating as $review)
                                                    <article class="sofar-review">
                                                        <div class="sofar-review__head">
                                                            <span class="sofar-review__avatar">{{ strtoupper(mb_substr((string) $review->name, 0, 1)) ?: '?' }}</span>
                                                            <div>
                                                                <strong class="sofar-review__name">{{ $review->name }}</strong>
                                                                <span class="sofar-review__date">{{ $review->created_at?->format('d M Y') }}</span>
                                                            </div>
                                                            <span class="sofar-stars sofar-review__stars" aria-label="Rated {{ (int) $review->rating }} out of 5">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i class="{{ $i <= (int) $review->rating ? 'fas' : 'far' }} fa-star" aria-hidden="true"></i>
                                                                @endfor
                                                            </span>
                                                        </div>
                                                        <p class="sofar-review__text">{{ $review->description }}</p>
                                                    </article>
                                                @empty
                                                    <p class="sofar-pd-muted">There are no reviews for this product yet. Be the first to write one.</p>
                                                @endforelse
                                            </div>

                                            <div class="col-12">
                                                <h3 class="sofar-pd-tabs__heading">Write a review</h3>
                                                @if (session('review_status'))
                                                    <div class="alert alert-success sofar-review-alert" role="status">{{ session('review_status') }}</div>
                                                @endif
                                                @if ($errors->hasAny(['name', 'email', 'rated', 'description', 'website']))
                                                    <div class="alert alert-danger sofar-review-alert" role="alert">
                                                        @foreach (['name', 'email', 'rated', 'description', 'website'] as $field)
                                                            @error($field)<div>{{ $message }}</div>@enderror
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <form action="{{ route('review.store', [$product->id]) }}" method="POST" class="sofar-review-form">
                                                    @csrf
                                                    {{-- Honeypot: hidden from people; bots that fill it are rejected. --}}
                                                    <div class="sofar-hp" aria-hidden="true">
                                                        <label for="reviewWebsite">Website</label>
                                                        <input type="text" name="website" id="reviewWebsite" tabindex="-1" autocomplete="off">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="rating">Your rating</label>
                                                        <select name="rated" id="rating" class="form-control">
                                                            <option value="5">★★★★★ Excellent</option>
                                                            <option value="4">★★★★☆ Good</option>
                                                            <option value="3">★★★☆☆ Average</option>
                                                            <option value="2">★★☆☆☆ Poor</option>
                                                            <option value="1">★☆☆☆☆ Terrible</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="descriptionTextarea">Your review</label>
                                                        <textarea class="form-control" name="description" rows="4" id="descriptionTextarea" required>{{ old('description') }}</textarea>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-sm-6">
                                                            <label for="inputName">Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="name" id="inputName" value="{{ old('name') }}" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label for="emailAddress">Email <span class="text-danger">*</span></label>
                                                            <input type="email" class="form-control" name="email" id="emailAddress" value="{{ old('email') }}" required>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="sofar-pd-buy__cart">Submit review</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <!-- Info -->
                        <div class="col-md-6">
                            <div class="sofar-pd-info">
                                @if ($product->category)
                                    <a href="{{ route('user.category', $product->category) }}" class="sofar-pd-info__category">{{ $product->category->name }}</a>
                                @endif
                                <h1 class="sofar-pd-info__title">{{ $title }}</h1>

                                <a href="#pdTabs" class="sofar-pd-info__rating js-open-reviews">
                                    <span class="sofar-stars" aria-label="Rated {{ $averageRating }} out of 5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $roundedRating ? 'fas' : 'far' }} fa-star" aria-hidden="true"></i>
                                        @endfor
                                    </span>
                                    <span>{{ $reviewCount > 0 ? number_format($averageRating, 1).' · '.number_format($reviewCount).' '.\Illuminate\Support\Str::plural('review', $reviewCount) : 'No reviews yet' }}</span>
                                </a>

                                <div class="sofar-pd-info__price">
                                    <span class="sofar-pd-info__now">₦{{ number_format($price) }}</span>
                                    @if ($discount > 0)
                                        <del class="sofar-pd-info__was">₦{{ number_format($oldPrice) }}</del>
                                        <span class="sofar-pd-info__save">Save {{ $discount }}%</span>
                                    @endif
                                </div>
                                @if ((float) $product->exchange_rate > 0)
                                    <p class="sofar-pd-info__usd">≈ {{ number_format((float) $product->exchange_rate) }} USD</p>
                                @endif

                                <p class="sofar-pd-info__stock"><i class="fa fa-check-circle" aria-hidden="true"></i> In stock</p>

                                <div class="sofar-pd-buy">
                                    <div class="sofar-pd-qty">
                                        <label for="qty2" class="sr-only">Quantity</label>
                                        <button type="button" class="sofar-pd-qty__btn decrement-btn" aria-label="Decrease quantity"><i class="fas fa-minus"></i></button>
                                        <input class="sofar-pd-qty__input" name="qty" id="qty2" type="number" value="1" min="1" inputmode="numeric">
                                        <button type="button" class="sofar-pd-qty__btn increment-btn" aria-label="Increase quantity"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <button type="button" class="sofar-pd-buy__cart" id="add2cart">
                                        <i class="ec ec-add-to-cart" aria-hidden="true"></i> Add to Cart
                                    </button>
                                </div>
                                <a href="{{ $whatsappUrl }}" class="sofar-pd-buy__whatsapp" target="_blank" rel="noopener">
                                    <i class="fab fa-whatsapp" aria-hidden="true"></i> Order on WhatsApp
                                </a>

                                @if ($product->specification)
                                    {{-- Downloads the datasheet; the file name shown to the customer is the product name. --}}
                                    <a href="{{ Media::url(Media::SPECIFICATIONS, $product->specification) }}"
                                       class="sofar-pd-info__spec btn btn-outline-success"
                                       download="{{ \Illuminate\Support\Str::slug($product->name) }}-specification.pdf">
                                        <i class="fas fa-file-download" aria-hidden="true"></i> Download specification (PDF)
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @if (count($related) > 0)
            <section class="sofar-pd-section">
                <h2 class="sofar-pd-section__title">You may also like</h2>
                <div class="row sofar-product-grid">
                    @foreach ($related as $related_pro)
                        <div class="col-6 col-md-4 col-xl-3">
                            @include('includes.product-card', ['product' => $related_pro])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if (count($recent) > 0)
            <section class="sofar-pd-section">
                <h2 class="sofar-pd-section__title">Recently viewed</h2>
                <div class="row sofar-product-grid">
                    @foreach ($recent as $recents)
                        <div class="col-6 col-md-4 col-xl-3">
                            @include('includes.product-card', ['product' => $recents])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(function () {
        var $addButton = $('#add2cart');
        var $overlay = $('#cartAddedOverlay');
        var $quantity = $('#qty2');

        // Rating link under the title opens the Reviews tab.
        $('.js-open-reviews').on('click', function () {
            $('#pd-reviews-tab').tab('show');
        });

        @if (session('review_status') || $errors->hasAny(['name', 'email', 'rated', 'description', 'website']))
            // Back from submitting a review: show the Reviews tab with the message.
            $('#pd-reviews-tab').tab('show');
            document.getElementById('pdTabs').scrollIntoView();
        @endif

        $('.increment-btn').on('click', function (event) {
            event.preventDefault();
            $quantity.val((Number($quantity.val()) || 0) + 1);
        });

        $('.decrement-btn').on('click', function (event) {
            event.preventDefault();
            $quantity.val(Math.max(1, (Number($quantity.val()) || 1) - 1));
        });

        function formatCurrency(amount) {
            return '₦' + Number(amount).toLocaleString('en-NG', {
                maximumFractionDigits: 2
            });
        }

        function closeBasket() {
            $overlay.prop('hidden', true).attr('aria-hidden', 'true');
        }

        $addButton.on('click', function (event) {
            event.preventDefault();

            var $button = $(this);
            var quantity = Number($('#qty2').val());

            if (!Number.isInteger(quantity) || quantity < 1) {
                $('#qty2').val(1).trigger('focus');
                return;
            }

            $button.prop('disabled', true);

            $.ajax({
                url: @json(route('carts.add', ['id' => $product->id])),
                type: 'GET',
                dataType: 'json',
                data: { qty: quantity }
            }).done(function (response) {
                $('.cartReload').text(response.cartQuantity);
                $('.cartReloads').text(formatCurrency(response.subtotal));

                $('#cartAddedName').text(response.item.name);
                $('#cartAddedItemMeta').text(
                    formatCurrency(response.item.price) + ' · Qty: ' + response.item.quantity
                );
                $('#cartAddedItemTotal').text(formatCurrency(response.item.total));
                $('#cartAddedSubtotal, #cartAddedTotal').text(formatCurrency(response.subtotal));

                var imageBase = @json(asset('images/products'));
                $('#cartAddedImage')
                    .attr('src', imageBase + '/' + encodeURIComponent(response.item.image))
                    .attr('alt', response.item.name);

                $overlay.prop('hidden', false).attr('aria-hidden', 'false');
                $('#cartAddedClose').trigger('focus');
            }).fail(function () {
                window.alert('We could not add this product to your cart. Please try again.');
            }).always(function () {
                $button.prop('disabled', false);
            });
        });

        $('#cartAddedClose').on('click', closeBasket);
        $overlay.on('click', function (event) {
            if (event.target === this) {
                closeBasket();
            }
        });

        $(document).on('keydown', function (event) {
            if (event.key === 'Escape' && !$overlay.prop('hidden')) {
                closeBasket();
            }
        });
    });
</script>
@endsection