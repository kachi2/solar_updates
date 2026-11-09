@php
    $whatsappNumber = config('services.whatsapp.number');
    $phones = config('sofar.contact.phones');
@endphp
<footer class="sofar-footer">
    <div class="container">
        <div class="row sofar-footer__main">
            <!-- Brand -->
            <div class="col-lg-4 mb-5 mb-lg-0">
                <a href="{{ route('index') }}" class="sofar-footer__logo" aria-label="Sofar Solar home">
                    <img src="{{ asset('/images/logo.png') }}" alt="Sofar Solar">
                </a>
                <p class="sofar-footer__about">
                    Sole distributors of SofarSolar products in Nigeria: inverters, batteries and solar panels for homes and businesses.
                </p>
                <a href="https://wa.me/{{ $whatsappNumber }}" class="sofar-footer__whatsapp" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
                </a>
            </div>

            <!-- Shop -->
            <div class="col-6 col-md-4 col-lg-2 mb-4 mb-md-0">
                <h2 class="sofar-footer__heading">Shop</h2>
                <ul class="sofar-footer__links">
                    @foreach ($menu_categories as $mnu)
                        <li><a href="{{ route('user.category', $mnu) }}">{{ $mnu->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Company -->
            <div class="col-6 col-md-4 col-lg-2 mb-4 mb-md-0">
                <h2 class="sofar-footer__heading">Company</h2>
                <ul class="sofar-footer__links">
                    @foreach ($menu as $ss)
                        <li><a href="{{ route('pages', $ss->slug) }}">{{ $ss->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-md-4 col-lg-4">
                <h2 class="sofar-footer__heading">Contact</h2>
                <ul class="sofar-footer__contact">
                    <li>
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <address>{{ config('sofar.contact.address') }}</address>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        <span>
                            @foreach ($phones as $phone)
                                <a href="tel:{{ $phone['tel'] }}">{{ $phone['display'] }}</a>@if (! $loop->last)<br>@endif
                            @endforeach
                        </span>
                    </li>
                    <li>
                        <i class="far fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:{{ config('sofar.contact.email') }}">{{ config('sofar.contact.email') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="sofar-footer__bottom">
        <div class="container">
            <p>© {{ date('Y') }} Sofar Solar Nigeria. All rights reserved.</p>
            <a href="{{ route('pages', 'contacts') }}">Store locator</a>
        </div>
    </div>
</footer>
