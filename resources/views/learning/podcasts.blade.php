@extends('layouts.app')

@section('title', 'Mantiq - BuildCast')

@section('content')

<div class="dashboard-page">

    <main class="dashboard-container">

        <!-- Header -->
        <header class="dashboard-header">
            <h1>BuildCast</h1>

            <a href="{{ route('dashboard') }}" class="coffee-button">
                Back to Dashboard
            </a>
        </header>

        <!-- Description -->
        <p class="dashboard-description">
            Explore our podcast library, listen to useful discussions,
            and gain new ideas and knowledge.
        </p>

        <!-- Podcasts -->
        <section class="learning-courses">

            <div class="learning-section-heading">
                <h2>Available Podcasts</h2>

                <span>
                    {{ $podcasts->count() }}
                    {{ $podcasts->count() === 1 ? 'Episode' : 'Episodes' }}
                </span>
            </div>

            @forelse($podcasts as $podcast)

                <article class="learning-course-card">

                    <div class="learning-course-info">

                        <h3>{{ $podcast->title }}</h3>

                        @if($podcast->description)
                            <p>{{ $podcast->description }}</p>
                        @endif

                        <div class="learning-course-meta">

                            @if($podcast->duration_minutes)
                                <span>
                                    Duration: {{ $podcast->duration_minutes }} minutes
                                </span>
                            @endif

                            @if($podcast->published_at)
                                <span>
                                    Published:
                                    {{ $podcast->published_at->format('M d, Y') }}
                                </span>
                            @endif

                        </div>

                        @if($podcast->media_url)
                            <div class="podcast-player">
                                <audio controls preload="none">
                                    <source src="{{ $podcast->media_url }}">
                                    Your browser does not support audio playback.
                                </audio>
                            </div>
                        @else
                            <p>Audio file is not available yet.</p>
                        @endif

                    </div>

                </article>

            @empty

                <div class="learning-empty-state">

                    <h3>No Podcasts Available Yet</h3>

                    <p>
                        There are no published podcast episodes available
                        at the moment. Please check back later.
                    </p>

                </div>

            @endforelse

        </section>

    </main>

</div>

@endsection
