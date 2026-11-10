@extends('layouts.app')
@section('title', 'Too many requests · ')
@section('content')
@php
    // Seconds until the visitor may try again (sent by Laravel's throttle).
    $retryAfter = isset($exception) ? (int) ($exception->getHeaders()['Retry-After'] ?? 0) : 0;
    $wait = $retryAfter > 90 ? ceil($retryAfter / 60).' minutes' : max($retryAfter, 30).' seconds';
@endphp
<main id="content" role="main" class="sofar-error">
    <div class="container">
        <section class="sofar-error__box">
            <p class="sofar-error__code" aria-hidden="true">429</p>
            <h1 class="sofar-error__title">Please slow down a little</h1>
            <p class="sofar-error__text">We've had a lot of requests from your connection in a short time. Please wait about  and try again.</p>

            <div class="sofar-error__actions">
                <a href="{{ url()->previous() }}" class="sofar-cart-btn">Try again</a>
                <a href="{{ route('index') }}" class="sofar-checkout-step__secondary sofar-account__browse">Go to homepage</a>
            </div>

            <a href="https://wa.me/{{ config('services.whatsapp.number') }}" class="sofar-footer__whatsapp sofar-error__help" target="_blank" rel="noopener">
                <i class="fab fa-whatsapp" aria-hidden="true"></i> Need help? Chat with us on WhatsApp
            </a>
        </section>
    </div>
</main>
@endsection
