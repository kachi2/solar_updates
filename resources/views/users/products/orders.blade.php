@extends('layouts.app')
@section('title', 'Order #'.$orders->order_No.' · ')
@section('content')
<main id="content" role="main" class="sofar-cart">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.orders') }}">My orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $orders->order_No }}</li>
            </ol>
        </nav>

        <div class="row">
            @include('includes.accountNav')
            <div class="col-xl-9">
                <div class="sofar-order-banner" role="status">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    <div>
                        <strong>Thank you, your order has been placed.</strong>
                        <span>We'll contact you to arrange delivery. You pay when your order arrives.</span>
                    </div>
                </div>
                @include('includes.order-details', ['order' => $orders, 'items' => $items])
            </div>
        </div>
    </div>
</main>
@endsection
