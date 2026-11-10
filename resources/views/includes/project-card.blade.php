{{--
    One project card (projects page, "More projects" on a project page).
    Usage: @include('includes.project-card', ['project' => $project])
--}}
@php
    $photoCount = count(array_filter(json_decode((string) $project->images, true) ?: [])) ?: ($project->image ? 1 : 0);
    $excerpt = \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags((string) $project->content))), 120);
    $projectUrl = route('projects.details', $project);
@endphp
<article class="sofar-project-card">
    <a href="{{ $projectUrl }}" class="sofar-project-card__link">
        <span class="sofar-project-card__media">
            @if ($project->image)
                <img src="{{ $project->imageSized(480) }}" alt="{{ $project->title }}" loading="lazy">
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
