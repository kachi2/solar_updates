@extends('layouts.app')
@section('title', 'News · ')
@section('content')
@php
    $excerpt = fn ($post, $limit) => \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags((string) $post->content))), $limit);
    $imageUrl = fn ($post) => $post->image_url;
    $featured = $news->first();
    $others = $news->slice(1);
@endphp
<main id="content" role="main" class="sofar-cart sofar-news">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">News</li>
            </ol>
        </nav>

        <header class="sofar-projects__intro">
            <h1 class="sofar-cart__title">News &amp; updates</h1>
            <p>Announcements, new products and updates from Sofar Solar Nigeria.</p>
        </header>

        @if (! $featured)
            <section class="sofar-cart-empty">
                <i class="far fa-newspaper sofar-cart-empty__icon" aria-hidden="true"></i>
                <h2 class="sofar-cart-empty__title">No news yet</h2>
                <p class="sofar-cart-empty__text">Check back soon for updates from Sofar Solar.</p>
            </section>
        @else
            <!-- Featured (newest) post -->
            <article class="sofar-news-featured">
                <a href="{{ route('news.details', $featured) }}" class="sofar-news-featured__link">
                    <span class="sofar-news-featured__media">
                        @if ($featured->image)
                            <img src="{{ $imageUrl($featured) }}" alt="{{ $featured->title }}">
                        @endif
                    </span>
                    <span class="sofar-news-featured__body">
                        <span class="sofar-news__label">Latest</span>
                        <time class="sofar-news__date" datetime="{{ $featured->created_at?->toDateString() }}">{{ $featured->created_at?->format('j F Y') }}</time>
                        <span class="sofar-news-featured__title">{{ $featured->title }}</span>
                        @if ($text = $excerpt($featured, 220))
                            <span class="sofar-news-featured__excerpt">{{ $text }}</span>
                        @endif
                        <span class="sofar-project-card__more">Read article <span aria-hidden="true">→</span></span>
                    </span>
                </a>
            </article>

            @if ($others->isNotEmpty())
                <h2 class="sofar-pd-section__title sofar-news__more-title">More news</h2>
                <div class="sofar-project-grid">
                    @foreach ($others as $post)
                        @include('includes.news-card', ['post' => $post])
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</main>
@endsection
