@extends('layouts.app')

@section('title', 'Mantiq - ' . $content->title)

@section('content')

<div class="dashboard-page">

    <main class="dashboard-container">

        <!-- Header -->
        <header class="dashboard-header">

            <h1>Course Details</h1>

            <a href="{{ route('learning.index', $content->track) }}"
               class="coffee-button">
                Back to Courses
            </a>

        </header>

        <!-- Success Message -->
        @if(session('success'))
            <div role="status"
                 style="padding: 12px 16px; margin-bottom: 20px; background: #f0eaf5; color: #800080; border-radius: 10px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Course Information -->
        <section class="learning-courses">

            <article class="learning-course-card">

                <div class="learning-course-info">

                    <h2>{{ $content->title }}</h2>

                    @if($content->description)
                        <p>{{ $content->description }}</p>
                    @else
                        <p>No description available for this course.</p>
                    @endif

                    <!-- Course Metadata -->
                    <div class="learning-course-meta">

                        @if($content->category)
                            <span>
                                Category: {{ $content->category->name }}
                            </span>
                        @endif

                        @if($content->level)
                            <span>
                                Level: {{ ucfirst($content->level) }}
                            </span>
                        @endif

                        @if($content->duration_minutes)
                            <span>
                                Duration: {{ $content->duration_minutes }} minutes
                            </span>
                        @endif

                        <span>
                            {{ $totalLessons }}
                            {{ $totalLessons === 1 ? 'Lesson' : 'Lessons' }}
                        </span>

                    </div>

                    <!-- Course Progress -->
                    @if($canView)

                        <div style="margin-top: 25px;">

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">

                                <strong>Course Progress</strong>

                                <strong style="color: #800080;">
                                    {{ $progressPercentage }}%
                                </strong>

                            </div>

                            <div style="width: 100%; height: 12px; background: #eee; border-radius: 20px; overflow: hidden;">

                                <div style="
                                    width: {{ $progressPercentage }}%;
                                    height: 100%;
                                    background: #800080;
                                    border-radius: 20px;
                                    transition: width 0.3s ease;
                                "></div>

                            </div>

                            <p style="margin-top: 10px; color: #777; font-size: 14px;">

                                {{ $completedLessons }} of {{ $totalLessons }}
                                {{ $totalLessons === 1 ? 'lesson' : 'lessons' }}
                                completed

                            </p>

                        </div>

                    @endif

                    <!-- Course Enrollment -->
                    @if($isEnrolled)

                        <div style="margin-top: 20px;">
                            <span class="learning-view-button">
                                Already Enrolled
                            </span>
                        </div>

                    @elseif($content->status === 'published' && (float) $content->price <= 0)

                        <div style="margin-top: 20px;">

                            @auth
                                <form action="{{ route('learning.enroll', $content->id) }}"
                                      method="POST">

                                    @csrf

                                    <button type="submit"
                                            class="learning-view-button">
                                        Enroll Now
                                    </button>

                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                   class="learning-view-button">
                                    Sign In to Enroll
                                </a>
                            @endauth

                        </div>

                    @elseif($content->status === 'published' && (float) $content->price > 0)

                        <div style="margin-top: 20px;">
                            <span style="color: #777;">
                                Paid Course — Enrollment Required
                            </span>
                        </div>

                    @endif

                </div>

            </article>

        </section>

        <!-- Course Lessons -->
        <section class="learning-courses">

            <div class="learning-section-heading">

                <h2>Course Lessons</h2>

                <span>
                    {{ $totalLessons }}
                    {{ $totalLessons === 1 ? 'Lesson' : 'Lessons' }}
                </span>

            </div>

            @forelse($content->lessons as $index => $lesson)

                <article class="learning-course-card">

                    <div class="learning-course-info">

                        <h3>
                            Lesson {{ $index + 1 }}:
                            {{ $lesson->title }}
                        </h3>

                        @if($lesson->description)
                            <p>{{ $lesson->description }}</p>
                        @endif

                        <!-- Lesson Completion Status -->
                        @if($canView)

                            @if($completedLessonIds->contains($lesson->id))

                                <span style="display: inline-block; margin-top: 10px; color: #198754; font-weight: bold;">
                                    ✓ Completed
                                </span>

                            @else

                                <span style="display: inline-block; margin-top: 10px; color: #777; font-weight: bold;">
                                    Not Completed
                                </span>

                            @endif

                        @endif

                    </div>

                    @if($canView)

                        <a href="{{ route('learning.lessons.show', $lesson->id) }}"
                           class="learning-view-button">
                            View Lesson
                        </a>

                    @else

                        <span style="color: #777;">
                            <i>Locked</i>
                        </span>

                    @endif

                </article>

            @empty

                <div class="learning-empty-state">

                    <h3>No Lessons Available Yet</h3>

                    <p>
                        Lessons for this course have not been added yet.
                        Please check back later.
                    </p>

                </div>

            @endforelse

        </section>

        <!-- Back Button -->
        <div class="learning-back-action">

            <a href="{{ route('learning.index', $content->track) }}"
               class="coffee-button">
                Back to Courses
            </a>

        </div>

    </main>

</div>

@endsection
