@extends('layouts.app')
@section('title', $project->title.' · ')
@section('content')
@php
    $photos = collect(json_decode((string) $project->images, true) ?: [])
        ->prepend($project->image)
        ->filter()
        ->unique()
        ->values();
    $photoUrl = fn ($file) => asset('images/projects/'.rawurlencode((string) $file));
    $whatsappUrl = 'https://wa.me/'.config('services.whatsapp.number').'?text='
        .rawurlencode("Hello Sofar Solar, I saw your project \"{$project->title}\" and I'd like a quote for something similar.");
@endphp
<main id="content" role="main" class="sofar-cart sofar-project">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pages', 'projects') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $project->title }}</li>
            </ol>
        </nav>

        <header class="sofar-project__head">
            <h1 class="sofar-cart__title">{{ $project->title }}</h1>
            <p class="sofar-project__meta">
                @if ($photos->count() > 0)
                    <span><i class="fas fa-camera" aria-hidden="true"></i> {{ $photos->count() }} {{ \Illuminate\Support\Str::plural('photo', $photos->count()) }}</span>
                @endif
                @if ($project->created_at)
                    <span><i class="far fa-calendar" aria-hidden="true"></i> {{ $project->created_at->format('F Y') }}</span>
                @endif
            </p>
        </header>

        <div class="row">
            <div class="col-lg-8">
                @if ($photos->isNotEmpty())
                    <div @class(['sofar-project-gallery', 'sofar-project-gallery--single' => $photos->count() === 1])>
                        @foreach ($photos as $photo)
                            <a href="{{ $photoUrl($photo) }}" data-fancybox="project-gallery" data-caption="{{ $project->title }}"
                               @class(['sofar-project-gallery__item', 'sofar-project-gallery__item--main' => $loop->first])
                               aria-label="Open photo {{ $loop->iteration }} of {{ $photos->count() }}">
                                <img src="{{ $photoUrl($photo) }}" alt="{{ $project->title }}, photo {{ $loop->iteration }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                            </a>
                        @endforeach
                    </div>
                @endif

                @if (trim(strip_tags((string) $project->content)) !== '')
                    <section class="sofar-project__about">
                        <h2 class="sofar-account__box-title">About this installation</h2>
                        <div class="sofar-prose">{!! $project->content !!}</div>
                    </section>
                @endif
            </div>

            <div class="col-lg-4">
                <aside class="sofar-project__side">
                    <div class="sofar-cart-summary">
                        <h2 class="sofar-cart-summary__title">Want a similar setup?</h2>
                        <p class="sofar-project__side-text">Tell us about your home or business and we'll recommend the right inverter, batteries and panels, with a quote.</p>
                        <a href="{{ $whatsappUrl }}" class="sofar-footer__whatsapp sofar-project__whatsapp" target="_blank" rel="noopener">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat with us on WhatsApp
                        </a>
                        <a href="tel:+2348135324241" class="sofar-project__call"><i class="fas fa-phone-alt" aria-hidden="true"></i> 0813 532 4241</a>
                    </div>
                    <a href="{{ route('pages', 'projects') }}" class="sofar-cart__continue sofar-project__back">← All projects</a>
                </aside>
            </div>
        </div>

        @if ($moreProjects->isNotEmpty())
            <section class="sofar-pd-section sofar-project__more">
                <h2 class="sofar-pd-section__title">More projects</h2>
                <div class="sofar-project-grid">
                    @foreach ($moreProjects as $other)
                        @include('includes.project-card', ['project' => $other])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</main>
@endsection
