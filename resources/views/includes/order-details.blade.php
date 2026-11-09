{{--
    One order (confirmation pages and the account's order details).
    Usage: @include('includes.order-details', ['order' => $orders, 'items' => $items])
--}}
@php
    $shipping = $order->shipping;
    $isPaid = (int) $order->is_paid === 1;
    $isDelivered = (int) $order->is_delivered === 1;
    $isDispatched = (int) $order->dispatch_status === 1;
    $methodLabel = match ($order->payment_method) {
        'card' => 'Card (Flutterwave)',
        'cash_delivery' => 'Pay on delivery',
        default => $order->payment_method ? \Illuminate\Support\Str::headline($order->payment_method) : '—',
    };
    $whatsappUrl = 'https://wa.me/'.config('services.whatsapp.number').'?text='
        .rawurlencode('Hello Sofar Solar, I have a question about order #'.$order->order_No.'.');
@endphp
<section class="sofar-order">
    <header class="sofar-order__head">
        <div>
            <h1 class="sofar-order__title">Order #{{ $order->order_No }}</h1>
            <p class="sofar-order__date">Placed {{ $order->created_at?->format('j M Y, g:i a') }}</p>
        </div>
        <div class="sofar-order__badges">
            <span @class(['sofar-status', 'sofar-status--ok' => $isPaid, 'sofar-status--wait' => ! $isPaid])>
                {{ $isPaid ? 'Paid' : 'Awaiting payment' }}
            </span>
            <span @class(['sofar-status', 'sofar-status--ok' => $isDelivered, 'sofar-status--info' => ! $isDelivered && $isDispatched, 'sofar-status--wait' => ! $isDelivered && ! $isDispatched])>
                {{ $isDelivered ? 'Delivered' : ($isDispatched ? 'Dispatched' : 'Processing') }}
            </span>
        </div>
    </header>

    <!-- Items -->
    <ul class="sofar-order__items">
        @foreach ($items as $item)
            <li class="sofar-order__item">
                <span class="sofar-order__thumb">
                    @if ($item->image)
                        <img src="{{ asset('images/products/'.rawurlencode((string) $item->image)) }}" alt="" loading="lazy">
                    @else
                        <i class="ec ec-shopping-bag" aria-hidden="true"></i>
                    @endif
                </span>
                <span class="sofar-order__item-info">
                    <span class="sofar-order__item-name">{{ $item->product_name }}</span>
                    <span class="sofar-order__item-meta">{{ (int) $item->qty }} × ₦{{ number_format((float) $item->price) }}</span>
                </span>
                <span class="sofar-order__item-total">₦{{ number_format((float) $item->payable) }}</span>
            </li>
        @endforeach
    </ul>

    <dl class="sofar-order__totals">
        <div>
            <dt>Delivery</dt>
            <dd class="sofar-cart-summary__muted">Arranged after your order</dd>
        </div>
        <div class="sofar-order__grand">
            <dt>Total</dt>
            <dd>₦{{ number_format((float) $order->amount) }}</dd>
        </div>
    </dl>

    <!-- Address + payment -->
    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="sofar-order__box">
                <h2 class="sofar-order__box-title">Delivery address</h2>
                @if ($shipping)
                    <address class="sofar-order__address">
                        <strong>{{ $shipping->receiver_name }}</strong>
                        <span>{{ $shipping->address }}</span>
                        <span>{{ collect([$shipping->city, $shipping->state])->filter()->implode(', ') }}</span>
                        @if ($shipping->receiver_phone)<span>{{ $shipping->receiver_phone }}</span>@endif
                        @if ($shipping->receiver_email)<span>{{ $shipping->receiver_email }}</span>@endif
                    </address>
                @else
                    <p class="sofar-cart-summary__muted mb-0">No address on this order.</p>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="sofar-order__box">
                <h2 class="sofar-order__box-title">Payment</h2>
                <dl class="sofar-order__facts">
                    <div><dt>Method</dt><dd>{{ $methodLabel }}</dd></div>
                    <div><dt>Amount</dt><dd>₦{{ number_format((float) $order->amount) }}</dd></div>
                    <div><dt>Status</dt><dd>{{ $isPaid ? 'Paid' : 'Awaiting payment' }}</dd></div>
                    @if ($order->payment_ref)
                        <div><dt>Reference</dt><dd>{{ $order->payment_ref }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="sofar-order__actions">
        <a href="{{ route('users.orders') }}" class="sofar-cart-btn">My orders</a>
        <a href="{{ route('pages', 'products') }}" class="sofar-checkout-step__secondary">Continue shopping</a>
        <a href="{{ $whatsappUrl }}" class="sofar-order__help" target="_blank" rel="noopener">
            <i class="fab fa-whatsapp" aria-hidden="true"></i> Questions about this order?
        </a>
    </div>
</section>
