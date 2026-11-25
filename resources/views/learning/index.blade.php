@extends('layouts.app')

@section('title', 'Mantiq - ' . ($track === 'tech' ? 'Tech Track' : 'B2B Route'))

@section('content')

<div class="dashboard-page">

    <main class="dashboard-container">

        <!-- Header -->
        <header class="dashboard-header">
            <h1>
                {{ $track === 'tech' ? 'Tech Track' : 'B2B Route' }}
            </h1>

            <a href="{{ route('dashboard') }}" class="coffee-button">
                Back to Dashboard
            </a>
        </header>

        <!-- Description -->
        <p class="dashboard-description">
            @if($track === 'tech')
                Explore technical courses and develop your programming skills.
            @else
                Explore business courses and build your professional skills.
            @endif
        </p>

        <!-- Courses -->
        <section class="learning-courses">

            <div class="learning-section-heading">
                <h2>Available Courses</h2>

                <span>
                    {{ $contents->count() }}
                    {{ $contents->count() === 1 ? 'Course' : 'Courses' }}
                </span>
            </div>

            @forelse($contents as $content)

                <article class="learning-course-card">

                    <div class="learning-course-info">

                        <h3>{{ $content->title }}</h3>

                        @if($content->description)
                            <p>{{ $content->description }}</p>
                        @else
                            <p>
                                No description available for this course.
                            </p>
                        @endif

                        <div class="learning-course-meta">

                            @if($content->category)
                                <span>
                                    {{ $content->category->name }}
                                </span>
                            @endif

                            @if($content->level)
                                <span>
                                    Level: {{ ucfirst($content->level) }}
                                </span>
                            @endif

                            @if($content->duration_minutes)
                                <span>
                                    {{ $content->duration_minutes }} minutes
                                </span>
                            @endif

                            <span>
                                {{ $content->lessons_count }}
                                {{ $content->lessons_count === 1 ? 'Lesson' : 'Lessons' }}
                            </span>

                        </div>

                    </div>

                    <a
                        href="{{ route('learning.show', $content->id) }}"
                        class="learning-view-button"
                    >
                        View Course
                    </a>

                </article>

            @empty

                <div class="learning-empty-state">

                    <h3>No Courses Available Yet</h3>

                    <p>
                        There are no published courses in this track yet.
                        Please check back later.
                    </p>

                    <a href="{{ route('dashboard') }}" class="coffee-button">
                        Back to Dashboard
                    </a>

                </div>

            @endforelse

        </section>

    </main>

</div>

@endsection
