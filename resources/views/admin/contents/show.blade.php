@extends('layouts.app')

@section('content')

<style>
    .show-section {
        padding: 30px;
        color: #252b3b;
    }

    .show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .show-header h1 {
        font-size: 27px;
        font-weight: 700;
        margin: 0 0 7px;
    }

    .show-header p {
        color: #8991a3;
        margin: 0;
        font-size: 14px;
    }

    .show-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .show-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 17px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .back-button {
        background: #f0f1f6;
        color: #606b80;
    }

    .edit-button {
        background: #e8efff;
        color: #3157a4;
    }

    .lessons-button {
        background: #e2f8ec;
        color: #218653;
    }

    .show-card {
        background: #fff;
        border: 1px solid #e9ebf1;
        border-radius: 14px;
        padding: 25px;
        margin-bottom: 22px;
    }

    .show-card h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #edf0f5;
        color: #293247;
    }

    .show-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .show-field label {
        display: block;
        color: #8991a3;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .show-field p {
        color: #293247;
        font-size: 14px;
        font-weight: 600;
        margin: 0;
        overflow-wrap: anywhere;
    }

    .show-description {
        color: #535d71 !important;
        font-weight: 400 !important;
        line-height: 1.8;
        white-space: pre-line;
    }

    .content-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .track-tech {
        background: #e9f2ff;
        color: #3474c5;
    }

    .track-business {
        background: #f1eaff;
        color: #8253c4;
    }

    .status-draft {
        background: #fff4dc;
        color: #b78118;
    }

    .status-published {
        background: #e2f8ec;
        color: #24945a;
    }

    .status-archived {
        background: #fce8e8;
        color: #c04c4c;
    }

    .content-cover {
        width: 100%;
        max-width: 300px;
        max-height: 220px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e9ebf1;
    }

    .show-link {
        color: #5546d8;
        text-decoration: none;
        font-weight: 600;
    }

    .show-link:hover {
        text-decoration: underline;
    }

    .show-empty {
        color: #9299a9;
        font-size: 13px;
    }

    @media (max-width: 600px) {
        .show-section {
            padding: 18px;
        }

        .show-grid {
            grid-template-columns: 1fr;
        }

        .show-header h1 {
            font-size: 22px;
        }
    }
</style>

<div class="admin-layout">

    {{-- Sidebar --}}
    <aside class="admin-sidebar">

        <div class="admin-brand">
            <div class="brand-icon">M</div>
            <span>Mantiq</span>
        </div>

        <div class="sidebar-section">
            <p class="sidebar-label">MAIN MENU</p>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                <span>▦</span> Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                <span>♙</span> Users
            </a>

            <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
                <span>▤</span> Categories
            </a>
        </div>

        <div class="sidebar-section">
            <p class="sidebar-label">CONTENT MANAGEMENT</p>

            <a href="{{ route('admin.contents.index', ['track' => 'tech']) }}"
                class="sidebar-link {{ $content->track == 'tech' ? 'active' : '' }}">
                <span>⌘</span> Tech Track
            </a>

            <a href="{{ route('admin.contents.index', ['track' => 'business']) }}"
                class="sidebar-link {{ $content->track == 'business' ? 'active' : '' }}">
                <span>▣</span> B2B Route
            </a>

            <a href="{{ route('admin.contents.index') }}"
                class="sidebar-link">
                <span>◫</span> All Contents
            </a>
        </div>

        <div class="sidebar-section">
            <p class="sidebar-label">OTHER</p>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                <span>◉</span> Dashboard
            </a>
        </div>

        <div class="sidebar-bottom">

            <div class="admin-profile">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>

                <div>
                    <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                    <small>Administrator</small>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    ↪ Logout
                </button>
            </form>

        </div>

    </aside>

    {{-- Main Content --}}
    <main class="admin-main">

        <header class="admin-header">
            <div>
                <span>Admin Panel / Content Management / Details</span>
            </div>
        </header>

        <section class="show-section">

            {{-- Page Heading --}}
            <div class="show-header">

                <div>
                    <h1>Content Details</h1>
                    <p>View the information and details of this content.</p>
                </div>

                <div class="show-actions">

                    <a href="{{ route('admin.contents.index') }}"
                        class="show-button back-button">
                        ← Back to Contents
                    </a>

                    <a href="{{ route('admin.contents.edit', $content->id) }}"
                        class="show-button edit-button">
                        Edit Content
                    </a>

                    {{-- Manage Lessons: Courses Only --}}
                    @if($content->type === 'course')
                    <a href="{{ route('admin.contents.lessons.index', $content->id) }}"
                        class="show-button lessons-button">
                        Manage Lessons
                    </a>
                    @endif

                </div>

            </div>

            {{-- Basic Information --}}
            <div class="show-card">

                <h3>Basic Information</h3>

                <div class="show-grid">

                    <div class="show-field">
                        <label>Content ID</label>
                        <p>#{{ $content->id }}</p>
                    </div>

                    <div class="show-field">
                        <label>Title</label>
                        <p>{{ $content->title }}</p>
                    </div>

                    <div class="show-field">
                        <label>Track</label>
                        <p>
                            <span class="content-badge track-{{ $content->track }}">
                                {{ ucfirst($content->track) }}
                            </span>
                        </p>
                    </div>

                    <div class="show-field">
                        <label>Content Type</label>
                        <p>{{ ucfirst($content->type) }}</p>
                    </div>

                    <div class="show-field">
                        <label>Category</label>
                        <p>{{ $content->category?->name ?? '—' }}</p>
                    </div>

                    <div class="show-field">
                        <label>Status</label>
                        <p>
                            <span class="content-badge status-{{ $content->status }}">
                                {{ ucfirst($content->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="show-field">
                        <label>Price</label>
                        <p>
                            {{ number_format((float) $content->price, 2) }}
                        </p>
                    </div>

                    <div class="show-field">
                        <label>Featured</label>
                        <p>{{ $content->is_featured ? 'Yes' : 'No' }}</p>
                    </div>

                    <div class="show-field">
                        <label>Views Count</label>
                        <p>{{ $content->views_count ?? 0 }}</p>
                    </div>

                    <div class="show-field">
                        <label>Created By</label>
                        <p>{{ $content->creator?->name ?? '—' }}</p>
                    </div>

                </div>

            </div>

            {{-- Description --}}
            <div class="show-card">

                <h3>Description</h3>

                @if($content->description)
                <p class="show-description">
                    {{ $content->description }}
                </p>
                @else
                <p class="show-empty">No description available.</p>
                @endif

            </div>

            {{-- Cover --}}
            @if($content->cover)
            <div class="show-card">

                <h3>Cover Image</h3>

                <img
                    src="{{ asset('storage/' . $content->cover) }}"
                    alt="{{ $content->title }}"
                    class="content-cover">

            </div>
            @endif

            {{-- Type Specific Information --}}
            <div class="show-card">

                <h3>{{ ucfirst($content->type) }} Details</h3>

                <div class="show-grid">

                    @if($content->type === 'course')

                    <div class="show-field">
                        <label>Level</label>
                        <p>{{ $content->level ? ucfirst($content->level) : '—' }}</p>
                    </div>

                    <div class="show-field">
                        <label>Duration</label>
                        <p>
                            {{ $content->duration_minutes !== null
                                    ? $content->duration_minutes . ' minutes'
                                    : '—' }}
                        </p>
                    </div>

                    @elseif($content->type === 'podcast')

                    <div class="show-field">
                        <label>Duration</label>
                        <p>
                            {{ $content->duration_minutes !== null
                                    ? $content->duration_minutes . ' minutes'
                                    : '—' }}
                        </p>
                    </div>

                    @elseif($content->type === 'book')

                    <div class="show-field">
                        <label>Pages</label>
                        <p>{{ $content->pages ?? '—' }}</p>
                    </div>

                    <div class="show-field">
                        <label>Reading Time</label>
                        <p>
                            {{ $content->read_minutes !== null
                                    ? $content->read_minutes . ' minutes'
                                    : '—' }}
                        </p>
                    </div>

                    @elseif($content->type === 'article')

                    <div class="show-field">
                        <label>Reading Time</label>
                        <p>
                            {{ $content->read_minutes !== null
                                    ? $content->read_minutes . ' minutes'
                                    : '—' }}
                        </p>
                    </div>

                    @endif

                    @if($content->media_url)
                    <div class="show-field">
                        <label>Media URL</label>
                        <p>
                            <a href="{{ $content->media_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="show-link">
                                Open Media
                            </a>
                        </p>
                    </div>
                    @endif

                    @if($content->file_path)
                    <div class="show-field">
                        <label>Attached File</label>
                        <p>
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($content->file_path) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="show-link">
                                Open File
                            </a>
                        </p>
                    </div>
                    @endif

                </div>

            </div>

            {{-- Dates --}}

            {{-- Course Lessons --}}
            @if($content->type === 'course')

            <div class="show-card">

                <h3>Course Lessons</h3>

                <div class="show-grid">

                    <div class="show-field">
                        <label>Total Lessons</label>
                        <p>{{ $content->lessons->count() }}</p>
                    </div>

                    <div class="show-field">
                        <label>Total Duration</label>
                        <p>
                            {{ $content->lessons->sum('duration_minutes') }} minutes
                        </p>
                    </div>

                </div>

                <div style="margin-top: 25px;">

                    @forelse($content->lessons->sortBy('sort_order') as $lesson)

                    <div style="
                    padding: 15px;
                    border: 1px solid #edf0f5;
                    border-radius: 10px;
                    margin-bottom: 12px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 15px;
                    flex-wrap: wrap;
                ">

                        <div>
                            <strong>{{ $lesson->sort_order }}. {{ $lesson->title }}</strong>

                            <p style="
                            color: #8991a3;
                            font-size: 12px;
                            margin: 6px 0 0;
                        ">
                                {{ $lesson->duration_minutes ?? 0 }} minutes
                            </p>
                        </div>

                        <a href="{{ route('admin.contents.lessons.show', [
                        $content->id,
                        $lesson->id
                    ]) }}"
                            class="show-link">
                            View Lesson
                        </a>

                    </div>

                    @empty

                    <p class="show-empty">
                        No lessons have been added to this course yet.
                    </p>

                    @endforelse

                </div>

            </div>

            @endif

            <div class="show-card">

                <h3>Publication & Dates</h3>

                <div class="show-grid">

                    <div class="show-field">
                        <label>Published At</label>
                        <p>
                            {{ $content->published_at?->format('d M Y, h:i A') ?? '—' }}
                        </p>
                    </div>

                    <div class="show-field">
                        <label>Created At</label>
                        <p>
                            {{ $content->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </p>
                    </div>

                    <div class="show-field">
                        <label>Last Updated</label>
                        <p>
                            {{ $content->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </p>
                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

@endsection