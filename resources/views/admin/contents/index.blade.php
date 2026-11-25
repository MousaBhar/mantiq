@extends('layouts.app')

@section('content')

<style>
    .contents-section {
        padding: 30px;
        color: #252b3b;
    }

    .contents-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .contents-header h1 {
        font-size: 27px;
        font-weight: 700;
        margin: 0 0 7px;
    }

    .contents-header p {
        color: #8991a3;
        margin: 0;
        font-size: 14px;
    }

    .contents-count {
        background: #fff;
        border: 1px solid #e9ebf1;
        border-radius: 12px;
        padding: 12px 18px;
        color: #606b80;
        font-size: 14px;
    }

    .contents-count strong {
        color: #5546d8;
        font-size: 20px;
        margin-left: 7px;
    }

    .contents-header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .content-add-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.2s ease;
    }

    .content-add-button:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .contents-filters {
        background: #fff;
        border: 1px solid #e9ebf1;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 22px;
    }

    .contents-filters h3 {
        font-size: 16px;
        margin: 0 0 17px;
        font-weight: 700;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(130px, 1fr));
        gap: 14px;
    }

    .filter-field label {
        display: block;
        font-size: 12px;
        color: #788195;
        margin-bottom: 7px;
        font-weight: 600;
    }

    .filter-field input,
    .filter-field select {
        width: 100%;
        height: 42px;
        padding: 0 11px;
        border: 1px solid #e2e5ed;
        border-radius: 8px;
        background: #fff;
        color: #333b4d;
        outline: none;
        font-size: 13px;
    }

    .filter-field input:focus,
    .filter-field select:focus {
        border-color: #6557d9;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 17px;
    }

    .filter-button,
    .reset-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }

    .filter-button {
        background: #5546d8;
        color: #fff;
    }

    .filter-button:hover {
        background: #4435c0;
    }

    .reset-button {
        background: #f0f1f6;
        color: #606b80;
    }

    .contents-table-card {
        background: #fff;
        border: 1px solid #e9ebf1;
        border-radius: 14px;
        overflow: hidden;
    }

    .contents-table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 22px;
        border-bottom: 1px solid #edf0f5;
        gap: 10px;
    }

    .contents-table-heading h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .contents-table-heading span {
        color: #9299a9;
        font-size: 12px;
    }

    .contents-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .contents-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1150px;
        text-align: left;
    }

    .contents-table thead {
        background: #f8f9fc;
    }

    .contents-table th {
        padding: 14px 17px;
        color: #8991a3;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .contents-table td {
        padding: 16px 17px;
        border-top: 1px solid #f0f1f5;
        font-size: 13px;
        color: #535d71;
        vertical-align: middle;
    }

    .contents-table tbody tr:hover {
        background: #fafaff;
    }

    .content-title {
        color: #293247;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .content-description {
        color: #9aa1af;
        font-size: 11px;
        max-width: 220px;
    }

    .content-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .track-tech {
        background: #e9f2ff;
        color: #3474c5;
    }

    .track-business {
        background: #f1eaff;
        color: #8253c4;
    }

    .track-standalone {
        background: #e2f8ec;
        color: #24945a;
    }

    .type-badge {
        background: #f1f2f6;
        color: #606b80;
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

    .contents-empty {
        text-align: center;
        padding: 55px 20px !important;
        color: #9299a9 !important;
    }

    .contents-empty strong {
        display: block;
        color: #424b60;
        font-size: 15px;
        margin-bottom: 7px;
    }

    .contents-pagination {
        padding: 18px 22px;
        border-top: 1px solid #edf0f5;
    }

    .contents-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .contents-pagination p {
        font-size: 12px;
        color: #9299a9;
    }

    .content-actions {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }

    .content-actions form {
        margin: 0;
    }

    .content-view-button,
    .content-edit-button,
    .content-delete-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 11px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: 0.2s;
    }

    .content-view-button {
        background: #e8f7f0;
        color: #21845a;
    }

    .content-view-button:hover {
        background: #d2f0e1;
        color: #176b47;
    }

    .content-edit-button {
        background: #e8efff;
        color: #3157a4;
    }

    .content-edit-button:hover {
        background: #d7e4ff;
    }

    .content-delete-button {
        background: #feecec;
        color: #c0392b;
    }

    .content-delete-button:hover {
        background: #fbdada;
    }

    .content-success {
        background: #e2f8ec;
        color: #24945a;
        padding: 13px 18px;
        border-radius: 9px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .filter-grid {
            grid-template-columns: repeat(2, minmax(130px, 1fr));
        }
    }

    @media (max-width: 600px) {
        .contents-section {
            padding: 18px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .contents-header h1 {
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
               class="sidebar-link {{ request('track') == 'tech' ? 'active' : '' }}">
                <span>⌘</span> Technical Content
            </a>

            <a href="{{ route('admin.contents.index', ['track' => 'business']) }}"
               class="sidebar-link {{ request('track') == 'business' ? 'active' : '' }}">
                <span>▣</span> Business Content
            </a>

            <a href="{{ route('admin.contents.index', ['track' => 'podcast_library']) }}"
               class="sidebar-link {{ request('track') == 'podcast_library' ? 'active' : '' }}">
                <span>♫</span> Podcast Library
            </a>

            <a href="{{ route('admin.contents.index') }}"
               class="sidebar-link {{ !request('track') && !request('type') ? 'active' : '' }}">
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
                <span>Admin Panel / Content Management</span>
            </div>
        </header>

        <section class="contents-section">

            {{-- Page Heading --}}
            <div class="contents-header">

                <div>
                    <h1>Content Management</h1>
                    <p>Manage and organize all content across Mantiq tracks.</p>
                </div>

                <div class="contents-header-actions">

                    <div class="contents-count">
                        Total Contents
                        <strong>{{ $contents->total() }}</strong>
                    </div>

                    <a href="{{ route('admin.contents.create') }}"
                       class="content-add-button">
                        + Add New Content
                    </a>

                </div>

            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="content-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="contents-filters">

                <h3>Filter Contents</h3>

                <form action="{{ route('admin.contents.index') }}" method="GET">

                    <div class="filter-grid">

                        <div class="filter-field">
                            <label for="search">Search by title</label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search content...">
                        </div>

                        <div class="filter-field">
                            <label for="track">Track / Library</label>

                            <select name="track" id="track">

                                <option value="">All Tracks</option>

                                <option value="tech"
                                    {{ request('track') == 'tech' ? 'selected' : '' }}>
                                    Technical Content
                                </option>

                                <option value="business"
                                    {{ request('track') == 'business' ? 'selected' : '' }}>
                                    Business Content
                                </option>

                                <option value="podcast_library"
                                    {{ request('track') == 'podcast_library' ? 'selected' : '' }}>
                                    Podcast Library
                                </option>

                            </select>
                        </div>

                        <div class="filter-field">
                            <label for="type">Content Type</label>

                            <select name="type" id="type">
                                <option value="">All Types</option>

                                @foreach([
                                    'course' => 'Course',
                                    'podcast' => 'Podcast',
                                    'book' => 'Book',
                                    'article' => 'Article'
                                ] as $value => $label)

                                    <option value="{{ $value }}"
                                        {{ request('type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>

                                @endforeach
                            </select>
                        </div>

                        <div class="filter-field">
                            <label for="status">Status</label>

                            <select name="status" id="status">
                                <option value="">All Statuses</option>

                                @foreach($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="filter-actions">

                        <button type="submit" class="filter-button">
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.contents.index') }}"
                           class="reset-button">
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            {{-- Contents Table --}}
            <div class="contents-table-card">

                <div class="contents-table-heading">
                    <h3>All Contents</h3>

                    <span>
                        Showing {{ $contents->count() }}
                        of {{ $contents->total() }} records
                    </span>
                </div>

                <div class="contents-table-wrapper">

                    <table class="contents-table">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Content</th>
                                <th>Track / Library</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($contents as $content)

                                <tr>

                                    <td>#{{ $content->id }}</td>

                                    <td>
                                        <div class="content-title">
                                            {{ $content->title }}
                                        </div>

                                        <div class="content-description">
                                            {{ \Illuminate\Support\Str::limit($content->description ?? 'No description', 65) }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($content->track === 'tech')

                                            <span class="content-badge track-tech">
                                                Technical Content
                                            </span>

                                        @elseif($content->track === 'business')

                                            <span class="content-badge track-business">
                                                Business Content
                                            </span>

                                        @elseif($content->track === null && $content->type === 'podcast')

                                            <span class="content-badge track-standalone">
                                                Podcast Library
                                            </span>

                                        @else

                                            <span class="content-badge track-standalone">
                                                Standalone
                                            </span>

                                        @endif
                                    </td>

                                    <td>
                                        <span class="content-badge type-badge">
                                            {{ ucfirst($content->type) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $content->category?->name ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $content->price !== null ? number_format((float) $content->price, 2) : '—' }}
                                    </td>

                                    <td>
                                        <span class="content-badge status-{{ $content->status }}">
                                            {{ ucfirst($content->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $content->creator?->name ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $content->created_at?->format('d M Y') ?? '—' }}
                                    </td>

                                    <td>
                                        <div class="content-actions">

                                            <a href="{{ route('admin.contents.show', $content->id) }}"
                                               class="content-view-button">
                                                View
                                            </a>

                                            <a href="{{ route('admin.contents.edit', $content->id) }}"
                                               class="content-edit-button">
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('admin.contents.destroy', $content->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this content?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="content-delete-button">
                                                    Delete
                                                </button>

                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="10" class="contents-empty">
                                        <strong>No Contents Found</strong>
                                        There are no content records matching your filters.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="contents-pagination">
                    {{ $contents->links() }}
                </div>

            </div>

        </section>

    </main>

</div>

{{-- Dynamic Content Type Filter --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const trackSelect = document.getElementById('track');
        const typeSelect = document.getElementById('type');

        const allowedTypes = @json(config('mantiq.tracks', []));

        const allTypes = [
            { value: 'course', label: 'Course' },
            { value: 'podcast', label: 'Podcast' },
            { value: 'book', label: 'Book' },
            { value: 'article', label: 'Article' }
        ];

        function updateTypeOptions(resetInvalid = false) {

            const selectedTrack = trackSelect.value;
            const previousType = typeSelect.value;

            let availableTypes = allTypes;

            // Podcast Library contains standalone podcasts only.
            if (selectedTrack === 'podcast_library') {

                availableTypes = [
                    { value: 'podcast', label: 'Podcast' }
                ];

            } else if (selectedTrack !== '') {

                const trackTypes = allowedTypes[selectedTrack] || [];

                availableTypes = allTypes.filter(type =>
                    trackTypes.includes(type.value)
                );

            }

            typeSelect.innerHTML = '';

            const allOption = document.createElement('option');
            allOption.value = '';
            allOption.textContent = 'All Types';

            typeSelect.appendChild(allOption);

            availableTypes.forEach(type => {

                const option = document.createElement('option');

                option.value = type.value;
                option.textContent = type.label;

                typeSelect.appendChild(option);

            });

            const isTypeAllowed = availableTypes.some(
                type => type.value === previousType
            );

            if (!resetInvalid && isTypeAllowed) {

                typeSelect.value = previousType;

            } else if (selectedTrack === 'podcast_library') {

                // Automatically select Podcast for Podcast Library.
                typeSelect.value = 'podcast';

            } else {

                typeSelect.value = '';

            }
        }

        // Preserve the current filter when the page loads.
        updateTypeOptions(false);

        // Update content types when the selected track changes.
        trackSelect.addEventListener('change', function () {
            updateTypeOptions(true);
        });

    });
</script>

@endsection
