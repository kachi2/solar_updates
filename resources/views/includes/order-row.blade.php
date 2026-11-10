{{--
    One order in a list (My orders, account overview).
    Usage: @include('includes.order-row', ['order' => $order])
--}}
@php
    $first = $order->items->first();
    $extra = $order->items->count() - 1;
@endphp
<li>
    <a href="{{ route('users.order-details', encrypt($order->order_No)) }}" class="sofar-orders__row">
        <span class="sofar-orders__thumbs" aria-hidden="true">
            @forelse ($order->items->take(3) as $item)
                <span class="sofar-orders__thumb">
                    @if ($item->image)
                        <img src="{{ $item->imageSized(480) }}" alt="" loading="lazy">
                    @else
                        <i class="ec ec-shopping-bag"></i>
                    @endif
                </span>
            @empty
                <span class="sofar-orders__thumb"><i class="ec ec-shopping-bag"></i></span>
            @endforelse
        </span>
        <span class="sofar-orders__main">
            <span class="sofar-orders__number">Order #{{ $order->order_No }}</span>
            <span class="sofar-orders__date">{{ $order->created_at?->format('j M Y') }}</span>
            <span class="sofar-orders__summary">{{ $first?->product_name ?? 'Order' }}@if ($extra > 0) <em>+{{ $extra }} more</em>@endif</span>
        </span>
        <span class="sofar-orders__side">
            <span class="sofar-orders__total">₦{{ number_format((float) $order->amount) }}</span>
            <span class="sofar-orders__badges">
                @include('includes.order-status-badges', ['order' => $order])
            </span>
        </span>
        <i class="fas fa-chevron-right sofar-orders__chevron" aria-hidden="true"></i>
    </a>
</li>
