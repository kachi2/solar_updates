@extends('layouts.app')
@section('title', 'Your cart · ')
@section('content')
@php
    $itemCount = (int) Cart::getTotalQuantity();
    // Cart::getTotal() always returns a formatted string ("39,900,000.00"); no cart
    // conditions are used, so the raw subtotal is the total.
    $total = (float) Cart::getSubTotal(false);
    $usdRate = (float) ($dollar_exchange_rate->rate ?? 0);

    // One WhatsApp message with every line in the cart.
    $whatsappLines = $cart->map(fn ($item) => '• '.$item->quantity.' × '.$item->name.' (₦'.number_format($item->price * $item->quantity).')');
    $whatsappText = "Hello Sofar Solar, I'd like to order:\n".$whatsappLines->implode("\n")."\nTotal: ₦".number_format($total);
    $whatsappUrl = 'https://wa.me/'.config('services.whatsapp.number').'?text='.rawurlencode($whatsappText);
@endphp
<main id="content" role="main" class="sofar-cart">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </nav>

        @if ($cart->isEmpty())
            <section class="sofar-cart-empty">
                <i class="ec ec-shopping-bag sofar-cart-empty__icon" aria-hidden="true"></i>
                <h1 class="sofar-cart-empty__title">Your cart is empty</h1>
                <p class="sofar-cart-empty__text">Inverters, batteries and panels you add will show up here.</p>
                <a href="{{ route('pages', 'products') }}" class="sofar-cart-btn">Browse products</a>
                @guest
                    <p class="sofar-cart-empty__auth">
                        Have an account? <a href="{{ route('login') }}">Log in</a> or <a href="{{ route('register') }}">create one</a>.
                    </p>
                @endguest
            </section>
        @else
            <div class="sofar-cart__head">
                <h1 class="sofar-cart__title">Your cart <span>{{ $itemCount }} {{ \Illuminate\Support\Str::plural('item', $itemCount) }}</span></h1>
                <a href="{{ route('pages', 'products') }}" class="sofar-cart__continue">← Continue shopping</a>
            </div>

            @if (session('message'))
                <p class="sofar-cart__notice" role="status">{{ session('message') }}</p>
            @endif

            <div class="row">
                <!-- Items -->
                <div class="col-lg-8">
                    <ul class="sofar-cart-items">
                        @foreach ($cart as $item)
                            @php
                                $product = $item->model;
                                $productUrl = $product ? route('product-details', $product) : '#';
                            @endphp
                            <li class="sofar-cart-item">
                                <a href="{{ $productUrl }}" class="sofar-cart-item__thumb">
                                    <img src="{{ asset('images/products/'.rawurlencode((string) ($product->image ?? $item->attributes->image))) }}" alt="{{ $item->name }}" loading="lazy">
                                </a>

                                <div class="sofar-cart-item__info">
                                    <a href="{{ $productUrl }}" class="sofar-cart-item__name">{{ $item->name }}</a>
                                    @if ($product?->category)
                                        <span class="sofar-cart-item__meta">{{ $product->category->name }}</span>
                                    @endif
                                    <span class="sofar-cart-item__unit">
                                        ₦{{ number_format($item->price) }} each
                                        @if ($product && (float) $product->price > (float) $item->price)
                                            <del>₦{{ number_format($product->price) }}</del>
                                        @endif
                                    </span>

                                    <div class="sofar-cart-item__controls">
                                        <div class="sofar-cart-qty" aria-label="Quantity">
                                            <form method="POST" action="{{ route('carts.update', encrypt($item->id)) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="rowId" value="{{ $item->id }}">
                                                <input type="hidden" name="qty" value="{{ max(1, $item->quantity - 1) }}">
                                                <button type="submit" class="sofar-cart-qty__btn" aria-label="Decrease quantity" @disabled($item->quantity <= 1)>−</button>
                                            </form>
                                            <span class="sofar-cart-qty__value">{{ $item->quantity }}</span>
                                            <form method="POST" action="{{ route('carts.update', encrypt($item->id)) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="rowId" value="{{ $item->id }}">
                                                <input type="hidden" name="qty" value="{{ $item->quantity + 1 }}">
                                                <button type="submit" class="sofar-cart-qty__btn" aria-label="Increase quantity">+</button>
                                            </form>
                                        </div>

                                        <form method="POST" action="{{ route('carts.destroy', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sofar-cart-item__remove">Remove</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="sofar-cart-item__total">₦{{ number_format($item->price * $item->quantity) }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Summary -->
                <div class="col-lg-4">
                    <aside class="sofar-cart-summary">
                        <h2 class="sofar-cart-summary__title">Order summary</h2>
                        <dl class="sofar-cart-summary__rows">
                            <div>
                                <dt>Subtotal</dt>
                                <dd>₦{{ number_format($total) }}</dd>
                            </div>
                            <div>
                                <dt>Delivery</dt>
                                <dd class="sofar-cart-summary__muted">Calculated at checkout</dd>
                            </div>
                        </dl>
                        <div class="sofar-cart-summary__total">
                            <span>Total</span>
                            <strong>₦{{ number_format($total) }}</strong>
                        </div>
                        @if ($usdRate > 0)
                            <p class="sofar-cart-summary__usd">≈ {{ number_format($total / $usdRate) }} USD</p>
                        @endif

                        <a href="{{ route('checkout.index') }}" class="sofar-cart-btn sofar-cart-btn--block">Checkout</a>
                        <a href="{{ $whatsappUrl }}" class="sofar-cart-summary__whatsapp" target="_blank" rel="noopener">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Order on WhatsApp instead
                        </a>
                        @guest
                            <p class="sofar-cart-summary__note">You'll be asked to log in or create an account at checkout.</p>
                        @endguest
                    </aside>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
