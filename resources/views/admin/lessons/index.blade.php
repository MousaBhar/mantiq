@extends('layouts.app')

@section('content')
<div class="admin-layout">

    {{-- Sidebar --}}
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <div class="brand-icon">M</div>
            <span>Mantiq Admin</span>
        </div>

        <div class="sidebar-section">
            <span class="sidebar-label">MAIN MENU</span>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                Users
            </a>

            <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
                Categories
            </a>

            <a href="{{ route('admin.contents.index') }}" class="sidebar-link active">
                Contents
            </a>
        </div>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="admin-main">

        <header class="admin-header">
            <div>
                <h1>Course Lessons</h1>
                <p>Manage lessons for this course.</p>
            </div>

            <a href="{{ route('admin.contents.lessons.create', $content) }}"
               class="lesson-add-button">
                + Add New Lesson
            </a>
        </header>

        <section class="lessons-section">

            <div class="section-heading">
                <div>
                    <h2>{{ $content->title }}</h2>
                    <p>Manage and organize course lessons.</p>
                </div>

                <a href="{{ route('admin.contents.index') }}"
                   class="back-button">
                    Back to Contents
                </a>
            </div>

            @if(session('success'))
                <div class="lesson-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="lesson-table-wrapper">
                <table class="lesson-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lesson Title</th>
                            <th>Description</th>
                            <th>Duration</th>
                            <th>Order</th>
                            <th>Media</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($lessons as $lesson)
                            <tr>
                                <td>{{ $loop->iteration + ($lessons->currentPage() - 1) * $lessons->perPage() }}</td>

                                <td>
                                    <strong>{{ $lesson->title }}</strong>
                                </td>

                                <td>
                                    {{ $lesson->description
                                        ? \Illuminate\Support\Str::limit($lesson->description, 50)
                                        : '—' }}
                                </td>

                                <td>
                                    {{ $lesson->duration_minutes
                                        ? $lesson->duration_minutes . ' min'
                                        : '—' }}
                                </td>

                                <td>{{ $lesson->sort_order }}</td>

                                <td>
                                    @if($lesson->media_url)
                                        <a href="{{ $lesson->media_url }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="lesson-media-link">
                                            Open Media
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    <div class="lesson-actions">

                                        <a href="{{ route('admin.contents.lessons.show', [$content, $lesson]) }}"
                                           class="lesson-view-button">
                                            View
                                        </a>

                                        <a href="{{ route('admin.contents.lessons.edit', [$content, $lesson]) }}"
                                           class="lesson-edit-button">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.contents.lessons.destroy', [$content, $lesson]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="lesson-delete-button">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="lesson-empty">
                                    No lessons have been added to this course yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="lesson-pagination">
                {{ $lessons->links() }}
            </div>

        </section>
    </main>
</div>

<style>
.admin-layout {
    display: flex;
    min-height: 100vh;
    background: #f5f7fb;
    font-family: Arial, sans-serif;
}

.admin-sidebar {
    width: 250px;
    flex-shrink: 0;
    background: #172033;
    color: white;
    padding: 25px 18px;
    display: flex;
    flex-direction: column;
}

.admin-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 45px;
}

.brand-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #4f7cff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar-label {
    color: #8d99ae;
    font-size: 11px;
    margin-bottom: 10px;
}

.sidebar-link {
    color: #cbd5e1;
    text-decoration: none;
    padding: 12px;
    border-radius: 8px;
}

.sidebar-link:hover,
.sidebar-link.active {
    background: #2b3955;
    color: white;
}

.sidebar-bottom {
    margin-top: auto;
}

.logout-button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #26334c;
    color: white;
    cursor: pointer;
}

.admin-main {
    flex: 1;
    min-width: 0;
    padding: 35px;
}

.admin-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 30px;
}

.admin-header h1 {
    margin: 0 0 8px;
    color: #172033;
}

.admin-header p,
.section-heading p {
    color: #718096;
    margin: 0;
}

.lesson-add-button,
.back-button {
    text-decoration: none;
    padding: 11px 18px;
    border-radius: 8px;
    font-weight: 600;
}

.lesson-add-button {
    background: #4169e1;
    color: white;
}

.back-button {
    background: #edf1f7;
    color: #344054;
}

.lessons-section {
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 3px 15px rgba(0,0,0,.04);
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 25px;
}

.section-heading h2 {
    color: #172033;
    margin: 0 0 8px;
}

.lesson-success {
    padding: 13px;
    margin-bottom: 20px;
    border-radius: 8px;
    background: #e8f7f0;
    color: #21845a;
}

.lesson-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.lesson-table {
    width: 100%;
    min-width: 950px;
    border-collapse: collapse;
}

.lesson-table th,
.lesson-table td {
    padding: 15px 12px;
    text-align: left;
    border-bottom: 1px solid #edf0f5;
    color: #475467;
}

.lesson-table th {
    background: #f8fafc;
    color: #344054;
    font-size: 13px;
}

.lesson-table td strong {
    color: #172033;
}

.lesson-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.lesson-actions a,
.lesson-delete-button {
    padding: 7px 10px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.lesson-view-button {
    background: #e8f7f0;
    color: #21845a;
}

.lesson-edit-button {
    background: #edf2ff;
    color: #4169e1;
}

.lesson-delete-button {
    background: #fff0f0;
    color: #d64545;
}

.lesson-media-link {
    color: #4169e1;
    text-decoration: none;
}

.lesson-empty {
    text-align: center !important;
    padding: 35px !important;
    color: #98a2b3 !important;
}

.lesson-pagination {
    margin-top: 20px;
}

@media (max-width: 768px) {
    .admin-sidebar {
        width: 190px;
    }

    .admin-main {
        padding: 18px;
    }

    .admin-header,
    .section-heading {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
@endsection
