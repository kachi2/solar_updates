@extends('layouts.app')
@section('title', 'Addresses · ')
@section('content')
<main id="content" role="main" class="sofar-cart">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.account') }}">My account</a></li>
                <li class="breadcrumb-item active" aria-current="page">Addresses</li>
            </ol>
        </nav>

        <div class="row">
            @include('includes.accountNav')
            <div class="col-xl-9">
                <div class="sofar-cart__head">
                    <h1 class="sofar-cart__title">Addresses</h1>
                    @if ($addresses->isNotEmpty())
                        <a href="{{ route('users.add-address') }}" class="sofar-cart-btn">+ Add address</a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="sofar-checkout__alert" role="status">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="sofar-checkout__alert sofar-checkout__alert--error" role="alert">{{ session('error') }}</div>
                @endif

                @if ($addresses->isEmpty())
                    <section class="sofar-cart-empty sofar-cart-empty--left">
                        <i class="fas fa-map-marker-alt sofar-cart-empty__icon" aria-hidden="true"></i>
                        <h2 class="sofar-cart-empty__title">No saved addresses</h2>
                        <p class="sofar-cart-empty__text">Add a delivery address and checkout will use it automatically.</p>
                        <a href="{{ route('users.add-address') }}" class="sofar-cart-btn">Add address</a>
                    </section>
                @else
                    <div class="sofar-addresses">
                        @foreach ($addresses as $address)
                            @php $isDefault = (int) $address->is_default === 1; @endphp
                            <article @class(['sofar-address', 'sofar-address--default' => $isDefault])>
                                <div class="sofar-address__top">
                                    <strong class="sofar-address__name">{{ $address->receiver_name ?: 'Address' }}</strong>
                                    @if ($isDefault)
                                        <span class="sofar-status sofar-status--info">Default</span>
                                    @endif
                                </div>
                                <address class="sofar-address__body">
                                    <span>{{ $address->address }}</span>
                                    <span>{{ collect([$address->city, $address->state])->filter()->implode(', ') }}</span>
                                    @if ($address->receiver_phone)<span>{{ $address->receiver_phone }}</span>@endif
                                    @if ($address->receiver_email)<span>{{ $address->receiver_email }}</span>@endif
                                </address>
                                <div class="sofar-address__actions">
                                    <a href="{{ route('users.update-address', encrypt($address->id)) }}">Edit</a>
                                    @if ($addresses->total() > 1)
                                        <a href="{{ route('address.delete', encrypt($address->id)) }}" class="sofar-address__remove js-confirm" data-confirm="Remove this address?">Remove</a>
                                    @endif
                                    @unless ($isDefault)
                                        <form method="POST" action="{{ route('address.default', encrypt($address->id)) }}">
                                            @csrf
                                            <button type="submit">Set as default</button>
                                        </form>
                                    @endunless
                                </div>
                            </article>
                        @endforeach

                        <a href="{{ route('users.add-address') }}" class="sofar-address sofar-address--add">
                            <span aria-hidden="true">+</span>
                            Add address
                        </a>
                    </div>

                    <div class="sofar-orders__pagination">{{ $addresses->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(function () {
        $('.js-confirm').on('click', function (event) {
            if (!window.confirm($(this).data('confirm'))) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
