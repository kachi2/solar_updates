{{--
    Standalone on purpose: no site layout, no database queries, so this page
    still shows when the database (or anything the layout needs) is down.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Something went wrong · Sofar Solar</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/font-awesome/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/theme.css') }}">
</head>
<body class="sofar-error-standalone">
    <header class="sofar-error-standalone__bar">
        <a href="{{ url('/') }}" aria-label="Sofar Solar home"><img src="{{ asset('images/logo.png') }}" alt="Sofar Solar"></a>
    </header>
    <main class="sofar-error">
        <div class="container">
            <section class="sofar-error__box">
                <p class="sofar-error__code" aria-hidden="true">500</p>
                <h1 class="sofar-error__title">Something went wrong on our side</h1>
                <p class="sofar-error__text">This isn't your fault. Please try again in a moment. If it keeps happening, message us and we'll help you finish your order.</p>

                <div class="sofar-error__actions">
                    <a href="{{ url()->current() }}" class="sofar-cart-btn">Try again</a>
                    <a href="{{ url('/') }}" class="sofar-checkout-step__secondary sofar-account__browse">Go to homepage</a>
                </div>

                <a href="https://wa.me/{{ config('services.whatsapp.number') }}" class="sofar-footer__whatsapp sofar-error__help" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat with us on WhatsApp
                </a>
            </section>
        </div>
    </main>
</body>
</html>
