@extends('layouts.app')
@use('App\Support\Media')
@section('title', 'Payment · ')
@section('content')
@php
    $subtotal = (float) Cart::getSubTotal(false);
    $itemCount = (int) Cart::getTotalQuantity();
    $usdRate = (float) ($dollar_exchange_rate->rate ?? 0);
@endphp
<main id="content" role="main" class="sofar-cart sofar-checkout">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('carts.index') }}">Cart</a></li>
                <li class="breadcrumb-item"><a href="{{ route('checkout.index') }}">Checkout</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
            </ol>
        </nav>

        <div class="sofar-cart__head">
            <h1 class="sofar-cart__title">
                Payment
                @isset($order)
                    <span>Order #{{ $order->order_No }}</span>
                @endisset
            </h1>
        </div>

        @if ($errors->any())
            <div class="sofar-checkout__alert sofar-checkout__alert--error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row">
            <div class="col-lg-7 col-xl-8">
                <!-- Delivering to -->
                <section class="sofar-checkout-step">
                    <div class="sofar-checkout-step__head">
                        <h2 class="sofar-checkout-step__title"><span>1</span> Delivering to</h2>
                    </div>
                    @if ($address)
                        <address class="sofar-checkout-address mb-0">
                            <strong>{{ $address->receiver_name }}</strong>
                            <span>{{ $address->address }}</span>
                            <span>{{ collect([$address->city, $address->state])->filter()->implode(', ') }}</span>
                            <span class="sofar-checkout-address__contact">
                                {{ collect([$address->receiver_phone, $address->receiver_email])->filter()->implode(' · ') }}
                            </span>
                        </address>
                    @endif
                </section>

                <!-- Pay -->
                <section class="sofar-checkout-step">
                    <div class="sofar-checkout-step__head">
                        <h2 class="sofar-checkout-step__title"><span>2</span> Pay with card</h2>
                    </div>
                    <div class="sofar-pay-method">
                        <i class="far fa-credit-card sofar-pay-method__icon" aria-hidden="true"></i>
                        <div>
                            <p class="sofar-pay-method__name">Visa, Mastercard or Verve</p>
                            <p class="sofar-pay-method__desc">
                                When you click <strong>Pay</strong>, a secure Flutterwave window opens to take your card
                                details. Sofar Solar never sees or stores your card number.
                            </p>
                        </div>
                    </div>
                    <ol class="sofar-pay-steps">
                        <li>Enter your card details in the Flutterwave window.</li>
                        <li>Confirm with the OTP or PIN your bank sends you.</li>
                        <li>You'll come back here with your order confirmation.</li>
                    </ol>
                </section>
            </div>

            <!-- Summary -->
            <div class="col-lg-5 col-xl-4">
                <aside class="sofar-cart-summary">
                    <h2 class="sofar-cart-summary__title">Order summary</h2>

                    <ul class="sofar-checkout-lines">
                        @foreach ($cart as $item)
                            <li>
                                <span class="sofar-checkout-lines__thumb">
                                    <img src="{{ Media::sized(Media::PRODUCTS, $item->model->image ?? $item->attributes->image, 480) }}" alt="" loading="lazy">
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
                        <span>To pay now</span>
                        <strong>₦{{ number_format($subtotal) }}</strong>
                    </div>
                    @if ($usdRate > 0)
                        <p class="sofar-cart-summary__usd">≈ {{ number_format($subtotal / $usdRate) }} USD</p>
                    @endif

                    <button type="button" id="payNowButton" class="sofar-cart-btn sofar-cart-btn--block">
                        <i class="fas fa-lock" aria-hidden="true"></i> Pay ₦{{ number_format($subtotal) }}
                    </button>
                    <p class="sofar-cart-summary__note">Secured by Flutterwave</p>
                </aside>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
    $(function () {
        var $payButton = $('#payNowButton');

        $payButton.on('click', function () {
            if (typeof FlutterwaveCheckout !== 'function') {
                window.alert('The payment window could not load. Please check your connection and try again.');
                return;
            }

            $payButton.prop('disabled', true);

            FlutterwaveCheckout({
                public_key: @json(config('services.flutterwave.public_key')),
                tx_ref: @json(config('sofar.orders.reference_prefix')) + Math.floor((Math.random() * 1000000) + 1),
                amount: @json($subtotal),
                currency: 'NGN',
                country: 'NG',
                payment_options: 'card, ussd',
                meta: {
                    purpose: 'Payment for Order'
                },
                customer: {
                    email: @json(auth()->user()->email),
                    phone_number: @json(auth()->user()->phone),
                    name: @json(auth()->user()->name)
                },
                callback: function (response) {
                    var transactionId = encodeURIComponent(String(response.transaction_id));
                    var confirmationUrl = @json(route('confirm.payment', ['id' => '__TRANSACTION_ID__']));
                    window.location = confirmationUrl.replace('__TRANSACTION_ID__', transactionId);
                },
                onclose: function () {
                    $payButton.prop('disabled', false);
                },
                customizations: {
                    title: 'Sofar Solar',
                    description: 'Payment for Order'
                }
            });
        });
    });
</script>
@endsection
