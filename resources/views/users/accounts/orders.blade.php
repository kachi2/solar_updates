@extends('layouts.app')
@section('title', 'My orders · ')
@section('content')
<main id="content" role="main" class="sofar-cart">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.account') }}">My account</a></li>
                <li class="breadcrumb-item active" aria-current="page">My orders</li>
            </ol>
        </nav>

        <div class="row">
            @include('includes.accountNav')
            <div class="col-xl-9">
                <div class="sofar-cart__head">
                    <h1 class="sofar-cart__title">
                        My orders
                        @if ($orders->total() > 0)
                            <span>{{ $orders->total() }} {{ \Illuminate\Support\Str::plural('order', $orders->total()) }}</span>
                        @endif
                    </h1>
                </div>

                @if ($orders->isEmpty())
                    <section class="sofar-cart-empty sofar-cart-empty--left">
                        <i class="ec ec-shopping-bag sofar-cart-empty__icon" aria-hidden="true"></i>
                        <h2 class="sofar-cart-empty__title">No orders yet</h2>
                        <p class="sofar-cart-empty__text">When you place an order it will show up here, with its payment and delivery status.</p>
                        <a href="{{ route('pages', 'products') }}" class="sofar-cart-btn">Browse products</a>
                    </section>
                @else
                    <ul class="sofar-orders">
                        @foreach ($orders as $order)
                            @include('includes.order-row', ['order' => $order])
                        @endforeach
                    </ul>

                    <div class="sofar-orders__pagination">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
