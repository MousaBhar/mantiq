@extends('layouts.app')

@section('title', 'Mantiq - Search')

@section('content')
<div class="dashboard-page">
    <main class="dashboard-container">
        <header class="dashboard-header">
            <h1>Search</h1>
            @auth
                <a href="{{ route('dashboard') }}" class="coffee-button">Back to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="coffee-button">Login</a>
            @endauth
        </header>

        <form action="{{ route('search') }}" method="GET" style="display:flex;gap:10px;margin-bottom:22px;">
            <input type="search" name="q" value="{{ $q }}" maxlength="80" placeholder="ابحث عن دورة أو درس…" dir="auto"
                   style="flex:1;padding:13px 16px;border:1px solid #e3d3e3;border-radius:12px;font-size:15px;background:#fff;">
            <button type="submit" class="learning-view-button" style="border:0;cursor:pointer;">Search</button>
        </form>

        @if($results === null)
            <p style="color:#777;">Type a skill or topic, for example “Laravel”, “Git” or “تسويق”.</p>
        @else
            <div class="learning-section-heading"><h2>Courses ({{ $results['contents']->count() }})</h2></div>
            @forelse($results['contents'] as $course)
                <a href="{{ route('learning.show', $course) }}" class="learning-course-card" style="display:block;text-decoration:none;color:inherit;margin-bottom:12px;">
                    <div class="learning-course-info">
                        <h3 dir="auto" style="margin:0 0 6px;">{{ $course->title }}</h3>
                        <p dir="auto" style="margin:0;color:#777;">{{ \Illuminate\Support\Str::limit($course->description, 140) }}</p>
                    </div>
                </a>
            @empty
                <p style="color:#777;">No matching courses.</p>
            @endforelse

            <div class="learning-section-heading" style="margin-top:24px;"><h2>Lessons ({{ $results['lessons']->count() }})</h2></div>
            @forelse($results['lessons'] as $lesson)
                <a href="{{ route('learning.show', $lesson->content_id) }}" class="learning-course-card" style="display:block;text-decoration:none;color:inherit;margin-bottom:12px;">
                    <div class="learning-course-info">
                        <h3 dir="auto" style="margin:0 0 6px;">{{ $lesson->title }}</h3>
                        <p dir="auto" style="margin:0;color:#777;">in {{ $lesson->content->title }}</p>
                    </div>
                </a>
            @empty
                <p style="color:#777;">No matching lessons.</p>
            @endforelse
        @endif
    </main>
</div>
@endsection
