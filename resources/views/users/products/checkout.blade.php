@extends('layouts.app')
@section('title', 'Checkout · ')
@section('content')
@php
    $address = $shipping->first();
    $subtotal = (float) Cart::getSubTotal(false);
    $itemCount = (int) Cart::getTotalQuantity();
    $usdRate = (float) ($dollar_exchange_rate->rate ?? 0);
    $selectedMethod = old('payment_method');
@endphp
<main id="content" role="main" class="sofar-cart sofar-checkout">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('carts.index') }}">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>

        <div class="sofar-cart__head">
            <h1 class="sofar-cart__title">Checkout</h1>
            <a href="{{ route('carts.index') }}" class="sofar-cart__continue">← Back to cart</a>
        </div>

        @if ($errors->any())
            <div class="sofar-checkout__alert sofar-checkout__alert--error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if (session('success'))
            <div class="sofar-checkout__alert" role="status">{{ session('success') }}</div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="row">
                <div class="col-lg-7 col-xl-8">
                    <!-- 1. Delivery address -->
                    <section class="sofar-checkout-step">
                        <div class="sofar-checkout-step__head">
                            <h2 class="sofar-checkout-step__title"><span>1</span> Delivery address</h2>
                            @if ($address)
                                <a href="{{ route('users.address') }}" class="sofar-checkout-step__link">Change</a>
                            @endif
                        </div>

                        @if ($address)
                            <address class="sofar-checkout-address">
                                <strong>{{ $address->receiver_name }}</strong>
                                <span>{{ $address->address }}</span>
                                <span>{{ collect([$address->city, $address->state])->filter()->implode(', ') }}</span>
                                <span class="sofar-checkout-address__contact">
                                    {{ collect([$address->receiver_phone, $address->receiver_email])->filter()->implode(' · ') }}
                                </span>
                            </address>
                            <button type="button" class="sofar-checkout-step__secondary" data-toggle="modal" data-target="#addressModal">+ Ship to a different address</button>
                        @else
                            <p class="sofar-checkout-step__empty">You haven't added a delivery address yet.</p>
                            <button type="button" class="sofar-cart-btn" data-toggle="modal" data-target="#addressModal">Add delivery address</button>
                        @endif
                    </section>

                    <!-- 2. Payment -->
                    <section class="sofar-checkout-step">
                        <div class="sofar-checkout-step__head">
                            <h2 class="sofar-checkout-step__title"><span>2</span> Payment</h2>
                        </div>

                        <div class="sofar-checkout-methods" role="radiogroup" aria-label="Payment method">
                            <label class="sofar-checkout-method">
                                <input type="radio" name="payment_method" value="card" required @checked($selectedMethod === 'card')>
                                <span class="sofar-checkout-method__body">
                                    <span class="sofar-checkout-method__name">Pay with card</span>
                                    <span class="sofar-checkout-method__desc">Visa, Mastercard or Verve, paid securely through Flutterwave.</span>
                                </span>
                                <i class="far fa-credit-card sofar-checkout-method__icon" aria-hidden="true"></i>
                            </label>

                            <label class="sofar-checkout-method">
                                <input type="radio" name="payment_method" value="cash_delivery" required @checked($selectedMethod === 'cash_delivery')>
                                <span class="sofar-checkout-method__body">
                                    <span class="sofar-checkout-method__name">Pay on delivery</span>
                                    <span class="sofar-checkout-method__desc">Pay when your order arrives. The delivery fee is paid before your items are shipped.</span>
                                </span>
                                <i class="fas fa-truck sofar-checkout-method__icon" aria-hidden="true"></i>
                            </label>
                        </div>
                    </section>
                </div>

                <!-- Summary -->
                <div class="col-lg-5 col-xl-4">
                    <aside class="sofar-cart-summary">
                        <div class="sofar-checkout-summary__head">
                            <h2 class="sofar-cart-summary__title">Order summary</h2>
                            <a href="{{ route('carts.index') }}" class="sofar-checkout-step__link">Edit</a>
                        </div>

                        <ul class="sofar-checkout-lines">
                            @foreach ($cart as $item)
                                <li>
                                    <span class="sofar-checkout-lines__thumb">
                                        <img src="{{ asset('images/products/'.rawurlencode((string) ($item->model->image ?? $item->attributes->image))) }}" alt="" loading="lazy">
                                        <span class="sofar-checkout-lines__qty">{{ $item->quantity }}</span>
                                    </span>
                                    <span class="sofar-checkout-lines__name">{{ $item->name }}</span>
                                    <span class="sofar-checkout-lines__price">₦{{ number_format($item->price * $item->quantity) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <dl class="sofar-cart-summary__rows">
                            <div>
                                <dt>Subtotal ({{ $itemCount }} {{ \Illuminate\Support\Str::plural('item', $itemCount) }})</dt>
                                <dd>₦{{ number_format($subtotal) }}</dd>
                            </div>
                            <div>
                                <dt>Delivery</dt>
                                <dd class="sofar-cart-summary__muted">Arranged after your order</dd>
                            </div>
                        </dl>
                        <div class="sofar-cart-summary__total">
                            <span>Total</span>
                            <strong>₦{{ number_format($subtotal) }}</strong>
                        </div>
                        @if ($usdRate > 0)
                            <p class="sofar-cart-summary__usd">≈ {{ number_format($subtotal / $usdRate) }} USD</p>
                        @endif

                        <button type="submit" class="sofar-cart-btn sofar-cart-btn--block" @disabled(! $address)>Place order</button>
                        @unless ($address)
                            <p class="sofar-cart-summary__note">Add a delivery address to place your order.</p>
                        @endunless
                    </aside>
                </div>
            </div>
        </form>
    </div>

    <!-- Add address -->
    <div class="modal fade sofar-checkout-modal" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('checkout.address') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title" id="addressModalTitle">New delivery address</h2>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="addrName">Receiver's name</label>
                        <input class="form-control" name="name" id="addrName" type="text" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="addrStreet">Street address</label>
                        <input class="form-control" name="address" id="addrStreet" type="text" value="{{ old('address') }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="addrCity">City</label>
                            <input class="form-control" name="city" id="addrCity" type="text" value="{{ old('city') }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="addrState">State</label>
                            <input class="form-control" name="state" id="addrState" type="text" value="{{ old('state') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="addrPhone">Phone</label>
                            <input class="form-control" name="phone" id="addrPhone" type="tel" value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="addrEmail">Email</label>
                            <input class="form-control" name="email" id="addrEmail" type="email" value="{{ old('email', auth()->user()->email) }}">
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" value="1" name="default" id="addrDefault" type="checkbox" checked>
                        <label class="custom-control-label" for="addrDefault">Use this as my delivery address</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="sofar-checkout-step__secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="sofar-cart-btn" name="modal" type="submit">Save address</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@section('scripts')
@if ($errors->hasAny(['name', 'address', 'phone', 'email', 'city', 'state']))
    <script>
        // Address form had errors: reopen it so the customer can fix them.
        $(function () { $('#addressModal').modal('show'); });
    </script>
@endif
@endsection
