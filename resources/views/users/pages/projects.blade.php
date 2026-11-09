@extends('layouts.app')
@section('title', 'Projects · ')
@section('content')
<main id="content" role="main" class="sofar-cart sofar-projects">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb sofar-cart__breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Projects</li>
            </ol>
        </nav>

        <header class="sofar-projects__intro">
            <h1 class="sofar-cart__title">Our projects</h1>
            <p>Solar installations we've completed for homes and businesses across Nigeria.
                @if ($projects->isNotEmpty())
                    <span>{{ $projects->count() }} {{ \Illuminate\Support\Str::plural('project', $projects->count()) }}</span>
                @endif
            </p>
        </header>

        @if ($projects->isEmpty())
            <section class="sofar-cart-empty">
                <i class="fas fa-solar-panel sofar-cart-empty__icon" aria-hidden="true"></i>
                <h2 class="sofar-cart-empty__title">Projects coming soon</h2>
                <p class="sofar-cart-empty__text">We're adding photos of our recent installations.</p>
            </section>
        @else
            <div class="sofar-project-grid">
                @foreach ($projects as $project)
                    @php
                        $photoCount = count(array_filter(json_decode((string) $project->images, true) ?: [])) ?: ($project->image ? 1 : 0);
                        $excerpt = \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags((string) $project->content))), 120);
                        $projectUrl = route('projects.details', $project);
                    @endphp
                    <article class="sofar-project-card">
                        <a href="{{ $projectUrl }}" class="sofar-project-card__link">
                            <span class="sofar-project-card__media">
                                @if ($project->image)
                                    <img src="{{ asset('images/projects/'.rawurlencode((string) $project->image)) }}" alt="{{ $project->title }}" loading="lazy">
                                @endif
                                @if ($photoCount > 1)
                                    <span class="sofar-project-card__photos"><i class="fas fa-camera" aria-hidden="true"></i> {{ $photoCount }}</span>
                                @endif
                            </span>
                            <span class="sofar-project-card__body">
                                <span class="sofar-project-card__title">{{ $project->title }}</span>
                                @if ($excerpt)
                                    <span class="sofar-project-card__excerpt">{{ $excerpt }}</span>
                                @endif
                                <span class="sofar-project-card__more">View project <span aria-hidden="true">→</span></span>
                            </span>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif

        <section class="sofar-projects__cta">
            <div>
                <h2>Planning a solar installation?</h2>
                <p>Tell us about your home or business and we'll recommend the right inverter, batteries and panels.</p>
            </div>
            <a href="https://wa.me/{{ config('services.whatsapp.number') }}?text={{ rawurlencode("Hello Sofar Solar, I'd like a quote for a solar installation.") }}" class="sofar-footer__whatsapp" target="_blank" rel="noopener">
                <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat with us on WhatsApp
            </a>
        </section>
    </div>
</main>
@endsection
