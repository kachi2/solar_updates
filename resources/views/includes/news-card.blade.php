{{--
    One news card (news page "More news", article page "More news").
    Usage: @include('includes.news-card', ['post' => $post])
--}}
@php
    $cardExcerpt = \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags((string) $post->content))), 110);
@endphp
<article class="sofar-project-card">
    <a href="{{ route('news.details', $post) }}" class="sofar-project-card__link">
        <span class="sofar-project-card__media">
            @if ($post->image)
                <img src="{{ $post->imageSized(480) }}" alt="{{ $post->title }}" loading="lazy">
            @endif
        </span>
        <span class="sofar-project-card__body">
            <time class="sofar-news__date" datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->format('j M Y') }}</time>
            <span class="sofar-project-card__title">{{ $post->title }}</span>
            @if ($cardExcerpt)
                <span class="sofar-project-card__excerpt">{{ $cardExcerpt }}</span>
            @endif
            <span class="sofar-project-card__more">Read article <span aria-hidden="true">→</span></span>
        </span>
    </a>
</article>
