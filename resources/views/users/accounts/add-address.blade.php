@extends('layouts.app')
@section('title', 'Add address · ')
@section('content')
<main id="content" role="main" class="sofar-cart">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.account') }}">My account</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.address') }}">Addresses</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add address</li>
            </ol>
        </nav>

        <div class="row">
            @include('includes.accountNav')
            <div class="col-xl-9">
                <div class="sofar-cart__head">
                    <h1 class="sofar-cart__title">Add address</h1>
                </div>
                <section class="sofar-form-card">
                    @include('includes.address-form', ['address' => null, 'isFirst' => $addresses->isEmpty()])
                </section>
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
