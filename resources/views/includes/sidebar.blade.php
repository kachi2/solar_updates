{{-- Mobile slide-out menu (opened by the ☰ button; uses the theme's "unfold" script). --}}
<aside id="sidebarHeader1" class="u-sidebar u-sidebar--left sofar-drawer u-unfold--css-animation u-unfold--hidden" aria-label="Shop by category">
    <div class="u-sidebar__scroller">
        <div class="u-sidebar__container">
            <div class="js-scrollbar u-sidebar__body">
                <div id="headerSidebarContent" class="u-sidebar__content sofar-drawer__content">
                    <div class="sofar-drawer__top">
                        <a href="{{ route('index') }}" class="sofar-drawer__logo" aria-label="Sofar Solar home">
                            <img src="{{ asset('/images/logo.png') }}" alt="Sofar Solar">
                        </a>
                        <button type="button" class="sofar-drawer__close" aria-label="Close menu"
                            aria-controls="sidebarHeader1"
                            aria-haspopup="true"
                            aria-expanded="false"
                            data-unfold-event="click"
                            data-unfold-hide-on-scroll="false"
                            data-unfold-target="#sidebarHeader1"
                            data-unfold-type="css-animation"
                            data-unfold-animation-in="fadeInLeft"
                            data-unfold-animation-out="fadeOutLeft"
                            data-unfold-duration="500">
                            <i class="ec ec-close-remove" aria-hidden="true"></i>
                        </button>
                    </div>

                    <nav aria-label="Categories">
                        <h2 class="sofar-drawer__heading">Shop by category</h2>
                        <ul class="sofar-drawer__cats">
                            @foreach ($menu_categories as $cat)
                                <li>
                                    <a href="{{ route('user.category', $cat) }}">
                                        <span class="sofar-drawer__thumb">
                                            @if ($cat->image)
                                                <img src="{{ asset('images/category/'.rawurlencode((string) $cat->image)) }}" alt="" loading="lazy">
                                            @else
                                                <i class="ec ec-shopping-bag" aria-hidden="true"></i>
                                            @endif
                                        </span>
                                        <span class="sofar-drawer__name">{{ $cat->name }}</span>
                                        <i class="fas fa-chevron-right sofar-drawer__chevron" aria-hidden="true"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</aside>
{{-- Dimmed page behind the open menu; tapping it closes the menu (theme closes on outside taps). --}}
<div class="sofar-drawer-backdrop" aria-hidden="true"></div>
