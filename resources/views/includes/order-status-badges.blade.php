{{--
    Payment + delivery status pills for an order (see App\Enums\PaymentStatus, App\Enums\OrderProgress).
    Usage: @include('includes.order-status-badges', ['order' => $order])
--}}
@php
    $paymentStatus = $order->paymentStatus();
    $progress = $order->progress();
@endphp
<span class="sofar-status sofar-status--{{ $paymentStatus->tone() }}">{{ $paymentStatus->label() }}</span>
<span class="sofar-status sofar-status--{{ $progress->tone() }}">{{ $progress->label() }}</span>
