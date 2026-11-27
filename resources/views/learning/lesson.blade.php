@extends('layouts.app')

@section('title', 'Mantiq - ' . $lesson->title)
@section('meta_description', \Illuminate\Support\Str::limit($lesson->description ?: $lesson->title, 150))
@section('assistant-lesson', $lesson->id)

@section('content')

<style>
    .lesson-video { position: relative; width: 100%; aspect-ratio: 16 / 9; border-radius: 16px; overflow: hidden; background: #1c1524; box-shadow: 0 10px 30px rgba(41,35,51,.15); }
    .lesson-video iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
    .lesson-body { line-height: 1.9; font-size: 16.5px; color: #292333; }
    .lesson-body h1, .lesson-body h2, .lesson-body h3 { color: #29212d; margin: 1.4em 0 .5em; }
    .lesson-body h2 { font-size: 22px; } .lesson-body h3 { font-size: 18px; }
    .lesson-body p { margin: .7em 0; }
    .lesson-body ul, .lesson-body ol { margin: .7em 0; padding-inline-start: 1.5em; list-style: revert; }
    .lesson-body code { background: #f5eaf5; color: #690069; padding: 2px 6px; border-radius: 6px; font-size: .92em; }
    .lesson-body pre { background: #1c1524; color: #f3e8f3; padding: 16px; border-radius: 12px; overflow-x: auto; direction: ltr; text-align: left; }
    .lesson-body pre code { background: none; color: inherit; padding: 0; }
    .lesson-body blockquote { border-inline-start: 4px solid #800080; margin: 1em 0; padding: .3em 1em; background: #faf5fa; border-radius: 8px; }
    .lesson-body a { color: #800080; text-decoration: underline; }
    .lesson-badge { display: inline-block; background: #f5eaf5; color: #800080; border-radius: 999px; padding: 3px 12px; font-size: 13px; font-weight: 700; }
    .lesson-pager { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
</style>

<div class="dashboard-page">

    <main class="dashboard-container">

        <header class="dashboard-header">
            <h1>Lesson Details</h1>
            <a href="{{ route('learning.show', $lesson->content_id) }}" class="coffee-button">Back to Course</a>
        </header>

        @if(session('success'))
            <div class="learning-course-card" style="margin-bottom: 20px;">
                <div class="learning-course-info">
                    <p style="color: green; margin: 0;">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <section class="learning-courses">
            <article class="learning-course-card">
                <div class="learning-course-info">

                    <h2>{{ $lesson->title }}</h2>

                    <p>{{ $lesson->description ?: 'No description available for this lesson.' }}</p>

                    <div class="learning-course-meta">
                        <span>Lesson {{ $lessonNumber ?? $lesson->sort_order }} of {{ $lessonTotal }}</span>
                        @if($lesson->duration_minutes)
                            <span>Duration: {{ $lesson->duration_minutes }} minutes</span>
                        @endif
                        @if($lesson->is_preview)
                            <span class="lesson-badge">Free preview</span>
                        @endif
                    </div>

                    <div style="margin-top: 20px;">
                        @if($isCompleted)
                            <span style="color: green; font-weight: bold;">✓ Lesson Completed</span>
                        @else
                            <span style="color: #b7791f; font-weight: bold;">Not Completed Yet</span>
                        @endif
                    </div>

                    @if(!$isCompleted && $canComplete)
                        <form action="{{ route('learning.lessons.complete', $lesson) }}" method="POST" style="margin-top: 20px;">
                            @csrf
                            <button type="submit" class="learning-view-button">Mark as Complete</button>
                        </form>
                    @elseif(!$canComplete)
                        <p style="margin-top: 16px; color: #777; font-size: 14px;">
                            This is a free preview. Enroll in the course to track your progress.
                        </p>
                    @endif

                </div>
            </article>
        </section>

        {{-- Video --}}
        @if($lesson->embed_url)
            <section class="learning-courses">
                <div class="lesson-video">
                    <iframe src="{{ $lesson->embed_url }}"
                            title="{{ $lesson->title }}"
                            loading="lazy"
                            allow="accelerometer; clipboard-write; encrypted-media; picture-in-picture; fullscreen"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen></iframe>
                </div>
            </section>
        @endif

        {{-- Written content --}}
        @if($lesson->body_html)
            <section class="learning-courses">
                <article class="learning-course-card">
                    <div class="learning-course-info lesson-body" dir="auto">
                        {!! $lesson->body_html !!}
                    </div>
                </article>
            </section>
        @endif

        {{-- External material --}}
        @if($lesson->media_url)
            <section class="learning-courses">
                <div class="learning-course-card">
                    <div class="learning-course-info">
                        <h3>Extra Material</h3>
                        <p>Open the supporting resource for this lesson.</p>
                        <a href="{{ $lesson->media_url }}" target="_blank" rel="noopener noreferrer" class="learning-view-button">
                            Open Lesson Material
                        </a>
                    </div>
                </div>
            </section>
        @endif

        @if(!$lesson->embed_url && !$lesson->body_html && !$lesson->media_url)
            <div class="learning-empty-state">
                <h3>No Media Available</h3>
                <p>Learning materials for this lesson have not been added yet. Please check back later.</p>
            </div>
        @endif

        {{-- Previous / next --}}
        <div class="lesson-pager">
            @if($previousLesson)
                <a href="{{ route('learning.lessons.show', $previousLesson->id) }}" class="coffee-button">← {{ \Illuminate\Support\Str::limit($previousLesson->title, 32) }}</a>
            @else
                <span></span>
            @endif

            @if($nextLesson)
                <a href="{{ route('learning.lessons.show', $nextLesson->id) }}" class="coffee-button">{{ \Illuminate\Support\Str::limit($nextLesson->title, 32) }} →</a>
            @endif
        </div>

        <div class="learning-back-action">
            <a href="{{ route('learning.show', $lesson->content_id) }}" class="coffee-button">Back to Course</a>
            <a href="{{ route('dashboard') }}" class="coffee-button">Back to Dashboard</a>
        </div>

    </main>

</div>

@endsection
