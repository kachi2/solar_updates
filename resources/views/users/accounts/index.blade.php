@extends('layouts.app')
@section('title', 'My account · ')
@section('content')
@php
    $user = auth()->user();
    $address = $addresses->first();
    // Some legacy accounts use an email as the name.
    $firstName = mb_convert_case(strtok(trim((string) $user->name), ' @') ?: 'there', MB_CASE_TITLE);
@endphp
<main id="content" role="main" class="sofar-cart">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My account</li>
            </ol>
        </nav>

        <div class="row">
            @include('includes.accountNav')
            <div class="col-xl-9 sofar-account">
                <div class="sofar-cart__head">
                    <div>
                        <h1 class="sofar-cart__title">Hi, {{ $firstName }}</h1>
                        <p class="sofar-account__since">Member since {{ $user->created_at?->format('M Y') }}</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="sofar-checkout__alert" role="status">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="sofar-checkout__alert sofar-checkout__alert--error" role="alert">{{ session('error') }}</div>
                @endif

                <div class="row">
                    <!-- Profile -->
                    <div class="col-md-6 mb-4">
                        <section class="sofar-account__box">
                            <div class="sofar-account__box-head">
                                <h2 class="sofar-account__box-title">Profile</h2>
                                <a href="{{ route('user.account.details') }}" class="sofar-checkout-step__link">Edit</a>
                            </div>
                            <dl class="sofar-order__facts">
                                <div><dt>Name</dt><dd>{{ $user->name }}</dd></div>
                                <div><dt>Email</dt><dd>{{ $user->email }}</dd></div>
                                @if ($user->phone)
                                    <div><dt>Phone</dt><dd>{{ $user->phone }}</dd></div>
                                @endif
                            </dl>
                            <a href="{{ route('user.account.details') }}" class="sofar-account__minor">Change password</a>
                        </section>
                    </div>

                    <!-- Default delivery address -->
                    <div class="col-md-6 mb-4">
                        <section class="sofar-account__box">
                            <div class="sofar-account__box-head">
                                <h2 class="sofar-account__box-title">Delivery address</h2>
                                @if ($address)
                                    <a href="{{ route('users.update-address', encrypt($address->id)) }}" class="sofar-checkout-step__link">Edit</a>
                                @endif
                            </div>
                            @if ($address)
                                <address class="sofar-order__address">
                                    <strong>{{ $address->receiver_name }}</strong>
                                    <span>{{ $address->address }}</span>
                                    <span>{{ collect([$address->city, $address->state])->filter()->implode(', ') }}</span>
                                    @if ($address->receiver_phone)<span>{{ $address->receiver_phone }}</span>@endif
                                </address>
                                <a href="{{ route('users.address') }}" class="sofar-account__minor">Manage addresses</a>
                            @else
                                <p class="sofar-cart-summary__muted">You haven't saved a delivery address yet.</p>
                                <a href="{{ route('users.add-address') }}" class="sofar-cart-btn">Add address</a>
                            @endif
                        </section>
                    </div>
                </div>

                <!-- Recent orders -->
                <section class="sofar-account__orders">
                    <div class="sofar-account__box-head">
                        <h2 class="sofar-account__box-title">Recent orders</h2>
                        @if ($orderCount > 0)
                            <a href="{{ route('users.orders') }}" class="sofar-checkout-step__link">View all ({{ $orderCount }})</a>
                        @endif
                    </div>

                    @if ($recentOrders->isEmpty())
                        <p class="sofar-cart-summary__muted mb-3">You haven't placed any orders yet.</p>
                        <a href="{{ route('pages', 'products') }}" class="sofar-checkout-step__secondary sofar-account__browse">Browse products</a>
                    @else
                        <ul class="sofar-orders">
                            @foreach ($recentOrders as $order)
                                @include('includes.order-row', ['order' => $order])
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>
        </div>
    </div>
</main>
@endsection
