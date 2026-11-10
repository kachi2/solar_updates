@extends('layouts.app')
@use('App\Support\Media')
@section('title', $news->title.' · ')
@section('content')
@php
    $imageUrl = fn ($file) => Media::url(Media::NEWS, $file);
    // Extra photos (the gallery field often repeats the cover image).
    $extraPhotos = collect(json_decode((string) $news->images, true) ?: [])
        ->filter()
        ->unique()
        ->reject(fn ($file) => $file === $news->image)
        ->values();
    $articleUrl = route('news.details', $news);
    $shareWhatsApp = 'https://wa.me/?text='.rawurlencode($news->title.' '.$articleUrl);
    $shareFacebook = 'https://www.facebook.com/sharer/sharer.php?u='.rawurlencode($articleUrl);
@endphp
<main id="content" role="main" class="sofar-cart sofar-article">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pages', 'news') }}">News</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $news->title }}</li>
            </ol>
        </nav>

        <article class="sofar-article__body">
            <header class="sofar-article__head">
                <p class="sofar-article__meta">
                    <span class="sofar-news__label">News</span>
                    @if ($news->created_at)
                        <time datetime="{{ $news->created_at->toDateString() }}">{{ $news->created_at->format('j F Y') }}</time>
                    @endif
                </p>
                <h1 class="sofar-article__title">{{ $news->title }}</h1>
            </header>

            @if ($news->image)
                <figure class="sofar-article__cover">
                    <img src="{{ $news->imageSized(1200) }}" alt="{{ $news->title }}">
                </figure>
            @endif

            @if (trim(strip_tags((string) $news->content)) !== '')
                <div class="sofar-prose sofar-article__text">{!! $news->content !!}</div>
            @endif

            @if ($extraPhotos->isNotEmpty())
                <div class="sofar-article__photos">
                    @foreach ($extraPhotos as $photo)
                        <a href="{{ $imageUrl($photo) }}" data-fancybox="article-gallery" data-caption="{{ $news->title }}" class="sofar-project-gallery__item" aria-label="Open photo {{ $loop->iteration }}">
                            <img src="{{ Media::sized(Media::NEWS, $photo, 480) }}" alt="{{ $news->title }}, photo {{ $loop->iteration }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            @endif

            <footer class="sofar-article__foot">
                <div class="sofar-article__share">
                    <span>Share</span>
                    <a href="{{ $shareWhatsApp }}" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
                    <a href="{{ $shareFacebook }}" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                </div>
                <a href="{{ route('pages', 'news') }}" class="sofar-cart__continue">← All news</a>
            </footer>
        </article>

        @if ($moreNews->isNotEmpty())
            <section class="sofar-pd-section sofar-project__more">
                <h2 class="sofar-pd-section__title">More news</h2>
                <div class="sofar-project-grid">
                    @foreach ($moreNews as $post)
                        @include('includes.news-card', ['post' => $post])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</main>
@endsection
