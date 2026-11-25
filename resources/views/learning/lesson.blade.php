@extends('layouts.app')

@section('title', 'Mantiq - ' . $lesson->title)

@section('content')

<div class="dashboard-page">

    <main class="dashboard-container">

        <!-- Header -->
        <header class="dashboard-header">

            <h1>Lesson Details</h1>

            <a href="{{ route('learning.show', $lesson->content_id) }}"
               class="coffee-button">
                Back to Course
            </a>

        </header>

        <!-- Success Message -->
        @if(session('success'))
            <div class="learning-course-card" style="margin-bottom: 20px;">
                <div class="learning-course-info">
                    <p style="color: green; margin: 0;">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Lesson Information -->
        <section class="learning-courses">

            <article class="learning-course-card">

                <div class="learning-course-info">

                    <h2>{{ $lesson->title }}</h2>

                    @if($lesson->description)
                        <p>{{ $lesson->description }}</p>
                    @else
                        <p>
                            No description available for this lesson.
                        </p>
                    @endif

                    <!-- Lesson Metadata -->
                    <div class="learning-course-meta">

                        <span>
                            Lesson {{ $lesson->sort_order }}
                        </span>

                        @if($lesson->duration_minutes)
                            <span>
                                Duration: {{ $lesson->duration_minutes }} minutes
                            </span>
                        @endif

                    </div>

                    <!-- Completion Status -->
                    <div style="margin-top: 20px;">

                        @if($isCompleted)

                            <span style="color: green; font-weight: bold;">
                                ✓ Lesson Completed
                            </span>

                        @else

                            <span style="color: #b7791f; font-weight: bold;">
                                Not Completed Yet
                            </span>

                        @endif

                    </div>

                    <!-- Mark as Complete Button -->
                    @if(!$isCompleted)

                        <form action="{{ route('learning.lessons.complete', $lesson) }}"
                              method="POST"
                              style="margin-top: 20px;">

                            @csrf

                            <button type="submit"
                                    class="learning-view-button">
                                Mark as Complete
                            </button>

                        </form>

                    @endif

                </div>

            </article>

        </section>

        <!-- Lesson Media -->
        <section class="learning-courses">

            <div class="learning-section-heading">
                <h2>Lesson Content</h2>
            </div>

            @if($lesson->media_url)

                <div class="learning-course-card">

                    <div class="learning-course-info">

                        <h3>Lesson Video / Media</h3>

                        <p>
                            You can access the learning material using the link below.
                        </p>

                        <a href="{{ $lesson->media_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="learning-view-button">
                            Open Lesson Material
                        </a>

                    </div>

                </div>

            @else

                <div class="learning-empty-state">

                    <h3>No Media Available</h3>

                    <p>
                        Learning materials for this lesson have not been added yet.
                        Please check back later.
                    </p>

                </div>

            @endif

        </section>

        <!-- Navigation -->
        <div class="learning-back-action">

            <a href="{{ route('learning.show', $lesson->content_id) }}"
               class="coffee-button">
                Back to Course
            </a>

            <a href="{{ route('dashboard') }}"
               class="coffee-button">
                Back to Dashboard
            </a>

        </div>

    </main>

</div>

@endsection
