<!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        /*
         * Page meta. Views set any of these sections (all optional):
         *   title (e.g. the product name), description, image (absolute URL),
         *   og_type (website | product | article), robots.
         * Section values arrive HTML-escaped, so they are decoded before being escaped once here.
         */
        // Decoded twice: once for Blade's escaping, once for entities saved in the content itself (e.g. &nbsp;).
        $seoText = fn (string $section, string $default = '') => trim(preg_replace('/[\s\x{00A0}]+/u', ' ', html_entity_decode(
            preg_replace('/<[^>]*>/', ' ', html_entity_decode($__env->yieldContent($section, $default), ENT_QUOTES | ENT_HTML5)),
            ENT_QUOTES | ENT_HTML5
        )));
        $seoBrand = 'Sofar Solar';
        $seoPageTitle = trim($seoText('title'), " ·|-");
        $seoTitle = $seoPageTitle !== ''
            ? $seoPageTitle.' | '.$seoBrand
            : $seoBrand.' Nigeria | Solar Inverters, Lithium Batteries & Solar Panels';
        $seoDescription = \Illuminate\Support\Str::limit(
            $seoText('description', 'Buy genuine Sofar solar inverters, lithium batteries, solar panels and accessories in Nigeria. Order online or on WhatsApp with nationwide delivery.'),
            160, '…'
        );
        $seoImage = $seoText('image') ?: asset('images/logo.png');
        // Private pages (account, cart, checkout, login/register) stay out of search results.
        $seoRoute = request()->route();
        $seoPrivate = $seoRoute && (request()->routeIs('carts.*', 'checkout.*', 'search')
            || collect($seoRoute->gatherMiddleware())->contains(fn ($m) => is_string($m) && preg_match('/^(auth|guest)\b/', $m)));
        $seoRobots = $seoText('robots') ?: ($seoPrivate ? 'noindex, follow' : 'index, follow');
        $seoPage = request()->integer('page');
        $seoCanonical = url()->current().($seoPage > 1 ? '?page='.$seoPage : '');
    @endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta name="theme-color" content="#ffffff">
    {{-- Link previews (WhatsApp, Facebook, X, LinkedIn) --}}
    <meta property="og:site_name" content="{{ $seoBrand }}">
    <meta property="og:locale" content="en_NG">
    <meta property="og:type" content="{{ $seoText('og_type') ?: 'website' }}">
    <meta property="og:title" content="{{ $seoPageTitle ?: $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoPageTitle ?: $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    @stack('head')
    <link rel="icon" href="{{asset('/images/fav2.png')}}">
            <!-- Google Fonts -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">
            <!-- CSS Implementing Plugins -->
            <link rel="stylesheet" href="{{asset('/frontend/vendor/font-awesome/css/fontawesome-all.min.css')}}">
            <link rel="stylesheet" href="{{asset('/frontend/css/font-electro.css')}}">
            <link rel="stylesheet" href="{{asset('/frontend/vendor/animate.css/animate.min.css')}}">
            <link rel="stylesheet" href="{{asset('/frontend/vendor/hs-megamenu/src/hs.megamenu.css')}}">
            <link rel="stylesheet" href="{{asset('/frontend/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
            <link rel="stylesheet" href="{{asset('/frontend/vendor/fancybox/jquery.fancybox.css')}}">
            <link rel="stylesheet" href="{{asset('/frontend/vendor/slick-carousel/slick/slick.css')}}">
            <link rel="stylesheet" href="{{asset('/frontend/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}">
            {{-- ?v= changes whenever the file changes, so browsers never keep a stale copy. --}}
            <link rel="stylesheet" href="{{ asset('/frontend/css/theme.css') }}?v={{ is_file($themeCss = public_path('frontend/css/theme.css')) ? filemtime($themeCss) : '' }}">
        </head>
    <body>

        @include('includes.header')
        @include('includes.cart-added-modal')
        @yield('content')
        @include('includes.footer')
        <a class="js-go-to u-go-to" href="#"
            data-position='{"bottom": 15, "right": 15 }'
            data-type="fixed"
            data-offset-top="400"
            data-compensation="#header"
            data-show-effect="slideInUp"
            data-hide-effect="slideOutDown">
            <span class="fas fa-arrow-up u-go-to__inner"></span>
        </a>
  <script src="{{asset('/frontend/vendor/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/jquery-migrate/dist/jquery-migrate.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/popper.js/dist/umd/popper.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/bootstrap/bootstrap.min.js')}}"></script>

  <!-- JS Implementing Plugins -->
  <script src="{{asset('/frontend/vendor/appear.js')}}"></script>
  <script src="{{asset('/frontend/vendor/jquery.countdown.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/hs-megamenu/src/hs.megamenu.js')}}"></script>
  <script src="{{asset('/frontend/vendor/svg-injector/dist/svg-injector.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/fancybox/jquery.fancybox.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/typed.js/lib/typed.min.js')}}"></script>
  <script src="{{asset('/frontend/vendor/slick-carousel/slick/slick.js')}}"></script>
  <script src="{{asset('/frontend/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>

  <!-- JS Electro -->
  <script src="{{asset('/frontend/js/hs.core.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.countdown.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.header.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.hamburgers.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.unfold.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.focus-state.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.malihu-scrollbar.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.validation.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.fancybox.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.onscroll-animation.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.slick-carousel.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.show-animation.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.svg-injector.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.go-to.js')}}"></script>
  <script src="{{asset('/frontend/js/components/hs.selectpicker.js')}}"></script>

  <!-- JS Plugins Init. -->
  <script>
      $(window).on('load', function () {
          // initialization of HSMegaMenu component
          $('.js-mega-menu').HSMegaMenu({
              event: 'hover',
              direction: 'horizontal',
              pageContainer: $('.container'),
              breakpoint: 767.98,
              hideTimeOut: 0
          });

          // initialization of svg injector module
          $.HSCore.components.HSSVGIngector.init('.js-svg-injector');
      });

      $(document).on('ready', function () {
          // initialization of header
          $.HSCore.components.HSHeader.init($('#header'));

          // initialization of animation
          $.HSCore.components.HSOnScrollAnimation.init('[data-animation]');

          // initialization of unfold component
          $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
              afterOpen: function () {
                  $(this).find('input[type="search"]').focus();
              }
          });

          // initialization of popups
          $.HSCore.components.HSFancyBox.init('.js-fancybox');

         
          // initialization of forms
          $.HSCore.components.HSFocusState.init();

          // initialization of form validation
          $.HSCore.components.HSValidation.init('.js-validate', {
              rules: {
                  confirmPassword: {
                      equalTo: '#signupPassword'
                  }
              }
          });

          // initialization of show animations
          $.HSCore.components.HSShowAnimation.init('.js-animation-link');

          // initialization of fancybox
          $.HSCore.components.HSFancyBox.init('.js-fancybox');

          // initialization of slick carousel
          $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

          // initialization of go to
          $.HSCore.components.HSGoTo.init('.js-go-to');

          // initialization of hamburgers
          $.HSCore.components.HSHamburgers.init('#hamburgerTrigger');

          // initialization of unfold component
          $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
              beforeClose: function () {
                  $('#hamburgerTrigger').removeClass('is-active');
              },
              afterClose: function() {
                  $('#headerSidebarList .collapse.show').collapse('hide');
              }
          });

          $('#headerSidebarList [data-toggle="collapse"]').on('click', function (e) {
              e.preventDefault();

              var target = $(this).data('target');

              if($(this).attr('aria-expanded') === "true") {
                  $(target).collapse('hide');
              } else {
                  $(target).collapse('show');
              }
          });

          // initialization of unfold component
          $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

          // initialization of select picker
          $.HSCore.components.HSSelectPicker.init('.js-select');
      });
  </script>
  <script src="{{ asset('/frontend/js/sofar-cart.js') }}?v={{ is_file($cartJs = public_path('frontend/js/sofar-cart.js')) ? filemtime($cartJs) : '' }}"></script>

  @yield('scripts')
</body>
</html>