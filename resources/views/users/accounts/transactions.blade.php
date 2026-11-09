@extends('layouts.app')
@section('title', 'Payments · ')
@section('content')
<main id="content" role="main" class="sofar-cart">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.account') }}">My account</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payments</li>
            </ol>
        </nav>

        <div class="row">
            @include('includes.accountNav')
            <div class="col-xl-9">
                <div class="sofar-cart__head">
                    <h1 class="sofar-cart__title">Payments</h1>
                    @if ($transactions->isNotEmpty())
                        <label class="sofar-tx__filter">
                            <span class="sr-only">Filter payments</span>
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <input type="search" id="txFilter" placeholder="Filter by order, reference or amount" autocomplete="off">
                        </label>
                    @endif
                </div>

                @if ($transactions->isEmpty())
                    <section class="sofar-cart-empty sofar-cart-empty--left">
                        <i class="far fa-credit-card sofar-cart-empty__icon" aria-hidden="true"></i>
                        <h2 class="sofar-cart-empty__title">No payments yet</h2>
                        <p class="sofar-cart-empty__text">Card payments you make for your orders will be listed here with their references.</p>
                        <a href="{{ route('users.orders') }}" class="sofar-cart-btn">View my orders</a>
                    </section>
                @else
                    <div class="sofar-tx">
                        <div class="sofar-tx__row sofar-tx__row--head" aria-hidden="true">
                            <span>Date</span>
                            <span>Description</span>
                            <span>Reference</span>
                            <span class="sofar-tx__amount">Amount</span>
                        </div>
                        <ul class="sofar-tx__list" id="txList">
                            @foreach ($transactions as $transaction)
                                <li class="sofar-tx__row">
                                    <span class="sofar-tx__date">
                                        {{ $transaction->created_at?->format('j M Y') }}
                                        <small>{{ $transaction->created_at?->format('g:i a') }}</small>
                                    </span>
                                    <span class="sofar-tx__desc">
                                        @if ($transaction->order_No)
                                            Payment for
                                            <a href="{{ route('users.order-details', encrypt($transaction->order_No)) }}">order #{{ $transaction->order_No }}</a>
                                            <small>{{ $transaction->payment_method === 'card' ? 'Card' : \Illuminate\Support\Str::headline((string) $transaction->payment_method) }}</small>
                                        @else
                                            {{-- Older payments were saved without an order number. --}}
                                            {{ $transaction->type === 'credit' ? 'Account credit' : 'Card payment' }}
                                        @endif
                                    </span>
                                    <span class="sofar-tx__ref">
                                        {{ $transaction->payment_ref ?: '—' }}
                                        @if ($transaction->external_ref)
                                            <small>{{ $transaction->external_ref }}</small>
                                        @endif
                                    </span>
                                    <span class="sofar-tx__amount">₦{{ number_format((float) $transaction->amount) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="sofar-tx__nomatch" id="txNoMatch" hidden>No payments on this page match your filter.</p>
                    </div>

                    <div class="sofar-orders__pagination">{{ $transactions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(function () {
        // Plain-text filter of the payments on this page (no regex, so any input is safe).
        var $rows = $('#txList > li');

        $('#txFilter').on('input', function () {
            var term = $.trim($(this).val()).toLowerCase();
            var shown = 0;

            $rows.each(function () {
                var match = term === '' || $(this).text().toLowerCase().indexOf(term) !== -1;
                $(this).prop('hidden', !match);
                shown += match ? 1 : 0;
            });

            $('#txNoMatch').prop('hidden', shown > 0);
        });
    });
</script>
@endsection
