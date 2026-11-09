@extends('layouts.app')
@section('title', 'Session expired · ')
@section('content')
<main id="content" role="main" class="sofar-error">
    <div class="container">
        <section class="sofar-error__box">
            <p class="sofar-error__code" aria-hidden="true">419</p>
            <h1 class="sofar-error__title">Your session expired</h1>
            <p class="sofar-error__text">For your security, forms stop working after the page has been open for a while. Go back, refresh the page and try again. Nothing was saved or charged.</p>

            <div class="sofar-error__actions">
                <a href="{{ url()->previous() }}" class="sofar-cart-btn">Go back and try again</a>
                <a href="{{ route('index') }}" class="sofar-checkout-step__secondary sofar-account__browse">Go to homepage</a>
            </div>
        </section>
    </div>
</main>
@endsection
