@extends('layouts.app')

@section('title', 'Mantiq - Edit Content')

@section('content')

@php
    $availableTracks = $tracks ?? array_keys(config('mantiq.tracks', []));
    $availableCategories = $categories ?? collect();
    $trackTypes = $trackTypes ?? config('mantiq.tracks', []);
    $standaloneTypes = $standaloneTypes ?? config('mantiq.standalone_types', []);
@endphp

<style>
    .content-form-section {
        padding: 30px;
        color: #252b3b;
        max-width: 1100px;
    }

    .content-form-header {
        margin-bottom: 25px;
    }

    .content-form-header h1 {
        font-size: 27px;
        font-weight: 700;
        margin: 0 0 7px;
    }

    .content-form-header p {
        color: #8991a3;
        font-size: 14px;
        margin: 0;
    }

    .content-form-card {
        background: #fff;
        border: 1px solid #e9ebf1;
        border-radius: 14px;
        padding: 26px;
        margin-bottom: 22px;
    }

    .content-form-card h2 {
        font-size: 17px;
        margin: 0 0 6px;
        color: #293247;
    }

    .content-form-card .form-description {
        color: #9299a9;
        font-size: 12px;
        margin: 0 0 23px;
    }

    .content-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .content-form-group {
        margin-bottom: 19px;
    }

    .content-form-group.full-width {
        grid-column: 1 / -1;
    }

    .content-form-group label {
        display: block;
        color: #515c70;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .content-form-group label span {
        color: #d9534f;
    }

    .content-form-group input,
    .content-form-group select,
    .content-form-group textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #e2e5ed;
        border-radius: 8px;
        padding: 12px;
        font-size: 13px;
        color: #333b4d;
        background: #fff;
        outline: none;
        font-family: inherit;
    }

    .content-form-group input,
    .content-form-group select {
        height: 44px;
    }

    .content-form-group textarea {
        resize: vertical;
        min-height: 110px;
    }

    .content-form-group input:focus,
    .content-form-group select:focus,
    .content-form-group textarea:focus {
        border-color: #6557d9;
    }

    .content-form-group select:disabled {
        background: #f0f1f6;
        color: #9299a9;
        cursor: not-allowed;
    }

    .content-field-note {
        color: #9299a9;
        font-size: 11px;
        margin-top: 6px;
    }

    .content-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 5px;
        color: #515c70;
        font-size: 13px;
    }

    .content-checkbox input {
        width: 17px;
        height: 17px;
        accent-color: #5546d8;
    }

    .content-errors {
        background: #fff0f0;
        border: 1px solid #f4cccc;
        color: #b83232;
        padding: 15px 20px;
        border-radius: 9px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .content-errors ul {
        margin: 7px 0 0;
        padding-left: 20px;
    }

    .content-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
    }

    .content-cancel-button,
    .content-save-button {
        padding: 12px 22px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
    }

    .content-cancel-button {
        background: #f0f1f6;
        color: #606b80;
    }

    .content-save-button {
        background: #5546d8;
        color: #fff;
    }

    .content-save-button:hover {
        background: #4435c0;
    }

    .content-cancel-button:hover {
        background: #e5e7ef;
    }

    @media(max-width: 700px) {
        .content-form-section {
            padding: 18px;
        }

        .content-form-grid {
            grid-template-columns: 1fr;
        }

        .content-form-group.full-width {
            grid-column: auto;
        }

        .content-form-card {
            padding: 19px;
        }
    }
</style>

<div class="admin-layout">

    <aside class="admin-sidebar">

        <div class="admin-brand">
            <span class="brand-icon">M</span>
            <span>MANTIQ</span>
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
                class="sidebar-link">
                <span>⌘</span> Tech Track
            </a>

            <a href="{{ route('admin.contents.index', ['track' => 'business']) }}"
                class="sidebar-link">
                <span>▣</span> B2B Route
            </a>

            <a href="{{ route('admin.contents.index') }}"
                class="sidebar-link active">
                <span>◫</span> All Contents
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

    <main class="admin-main">

        <header class="admin-header">
            <div>
                <span>Admin Panel / Content Management / Edit Content</span>
            </div>
        </header>

        <section class="content-form-section">

            <div class="content-form-header">
                <h1>Edit Content</h1>
                <p>Update the information of your existing content.</p>
            </div>

            @if($errors->any())
                <div class="content-errors">
                    <strong>Please fix the following errors:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.contents.update', $content->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                {{-- Basic Information --}}
                <div class="content-form-card">

                    <h2>Basic Information</h2>
                    <p class="form-description">
                        Edit the main details of your content.
                    </p>

                    <div class="content-form-grid">

                        <div class="content-form-group full-width">
                            <label for="title">Content Title <span>*</span></label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title', $content->title) }}"
                                required>
                        </div>

                        <div class="content-form-group full-width">
                            <label for="description">Description</label>

                            <textarea
                                id="description"
                                name="description">{{ old('description', $content->description) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Classification --}}
                <div class="content-form-card">

                    <h2>Content Classification</h2>
                    <p class="form-description">
                        Choose the track, type, and category.
                    </p>

                    <div class="content-form-grid">

                        <div class="content-form-group">
                            <label for="track">Track</label>

                            <select id="track" name="track">

                                <option value=""
                                    {{ old('track', $content->track) === null ? 'selected' : '' }}>
                                    Podcast Library
                                </option>

                                @foreach($availableTracks as $track)
                                    <option
                                        value="{{ $track }}"
                                        {{ old('track', $content->track) == $track ? 'selected' : '' }}>
                                        {{ ucfirst($track) }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="content-form-group">
                            <label for="type">Content Type <span>*</span></label>

                            <select id="type" name="type" required></select>
                        </div>

                        <div class="content-form-group full-width">
                            <label for="category_id">Category</label>

                            <select id="category_id" name="category_id">
                                <option value="">No Category</option>

                                @foreach($availableCategories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        data-track="{{ $category->track }}"
                                        {{ old('category_id', $content->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="content-field-note">
                                Only categories belonging to the selected track will be available.
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Pricing and Status --}}
                <div class="content-form-card">

                    <h2>Pricing & Status</h2>
                    <p class="form-description">
                        Set the price and publication status.
                    </p>

                    <div class="content-form-grid">

                        <div class="content-form-group">
                            <label for="price">Price</label>

                            <input
                                type="number"
                                id="price"
                                name="price"
                                value="{{ old('price', $content->price) }}"
                                min="0"
                                step="0.01">
                        </div>

                        <div class="content-form-group">
                            <label for="status">Status <span>*</span></label>

                            <select id="status" name="status" required>
                                <option value="draft"
                                    {{ old('status', $content->status) == 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="published"
                                    {{ old('status', $content->status) == 'published' ? 'selected' : '' }}>
                                    Published
                                </option>

                                <option value="archived"
                                    {{ old('status', $content->status) == 'archived' ? 'selected' : '' }}>
                                    Archived
                                </option>
                            </select>
                        </div>

                        <div class="content-form-group full-width">
                            <label class="content-checkbox">
                                <input
                                    type="checkbox"
                                    name="is_featured"
                                    value="1"
                                    {{ old('is_featured', $content->is_featured) ? 'checked' : '' }}>
                                Mark this content as Featured
                            </label>
                        </div>

                    </div>
                </div>

                {{-- Additional Information --}}
                <div class="content-form-card">

                    <h2>Additional Information</h2>
                    <p class="form-description">
                        These fields are optional and depend on the content type.
                    </p>

                    <div class="content-form-grid">

                        <div class="content-form-group" id="level-group">
                            <label for="level">Level</label>

                            <select name="level" id="level">
                                <option value="">Select Level</option>

                                <option value="beginner"
                                    {{ old('level', $content->level) == 'beginner' ? 'selected' : '' }}>
                                    Beginner
                                </option>

                                <option value="intermediate"
                                    {{ old('level', $content->level) == 'intermediate' ? 'selected' : '' }}>
                                    Intermediate
                                </option>

                                <option value="advanced"
                                    {{ old('level', $content->level) == 'advanced' ? 'selected' : '' }}>
                                    Advanced
                                </option>
                            </select>
                        </div>

                        <div class="content-form-group" id="duration-group">
                            <label for="duration_minutes">Duration (Minutes)</label>

                            <input
                                type="number"
                                name="duration_minutes"
                                id="duration_minutes"
                                value="{{ old('duration_minutes', $content->duration_minutes) }}"
                                min="1">
                        </div>

                        <div class="content-form-group" id="pages-group">
                            <label for="pages">Number of Pages</label>

                            <input
                                type="number"
                                name="pages"
                                id="pages"
                                value="{{ old('pages', $content->pages) }}"
                                min="1">
                        </div>

                        <div class="content-form-group" id="read-minutes-group">
                            <label for="read_minutes">Reading Time (Minutes)</label>

                            <input
                                type="number"
                                name="read_minutes"
                                id="read_minutes"
                                value="{{ old('read_minutes', $content->read_minutes) }}"
                                min="1">
                        </div>

                    </div>
                </div>

                {{-- Media --}}
                <div class="content-form-card">

                    <h2>Media Information</h2>
                    <p class="form-description">
                        Add cover and media references.
                    </p>

                    <div class="content-form-grid">

                        <div class="content-form-group">
                            <label for="cover">Cover Image Path</label>

                            <input
                                type="text"
                                id="cover"
                                name="cover"
                                value="{{ old('cover', $content->cover) }}">
                        </div>

                        <div class="content-form-group">
                            <label for="media_url">Media URL</label>

                            <input
                                type="url"
                                id="media_url"
                                name="media_url"
                                value="{{ old('media_url', $content->media_url) }}">
                        </div>

                        <div class="content-form-group full-width">
                            <label for="file_path">File Path</label>

                            <input
                                type="text"
                                id="file_path"
                                name="file_path"
                                value="{{ old('file_path', $content->file_path) }}">
                        </div>

                    </div>
                </div>

                {{-- Actions --}}
                <div class="content-form-actions">

                    <a href="{{ route('admin.contents.index') }}"
                        class="content-cancel-button">
                        Cancel
                    </a>

                    <button type="submit" class="content-save-button">
                        Update Content
                    </button>

                </div>

            </form>

        </section>

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const trackSelect = document.getElementById('track');
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category_id');

        const allowedTypes = @json($trackTypes);
        const standaloneTypes = @json($standaloneTypes);

        const initialType = @json(old('type', data_get($content, 'type')));
        const initialCategory = @json(old('category_id', data_get($content, 'category_id')));

        const typeLabels = {
            course: 'Course',
            podcast: 'Podcast',
            book: 'Book',
            article: 'Article'
        };

        const categoryOptions = Array.from(categorySelect.options)
            .filter(option => option.value !== '')
            .map(option => ({
                value: option.value,
                text: option.text,
                track: option.dataset.track
            }));

        function updateTypes(selectedType = '') {

            const selectedTrack = trackSelect.value;

            const types = selectedTrack === ''
                ? standaloneTypes
                : (allowedTypes[selectedTrack] || []);

            typeSelect.innerHTML = '';

            types.forEach(function(type) {

                const option = document.createElement('option');

                option.value = type;
                option.textContent = typeLabels[type] || type;

                if (type === selectedType) {
                    option.selected = true;
                }

                typeSelect.appendChild(option);
            });

            updateAdditionalFields();
        }

        function updateCategories(selectedCategory = '') {

            const selectedTrack = trackSelect.value;

            categorySelect.innerHTML = '<option value="">No Category</option>';

            categorySelect.disabled = selectedTrack === '';

            if (selectedTrack === '') {
                return;
            }

            categoryOptions.forEach(function(category) {

                if (category.track === selectedTrack) {

                    const option = document.createElement('option');

                    option.value = category.value;
                    option.textContent = category.text;

                    if (category.value == selectedCategory) {
                        option.selected = true;
                    }

                    categorySelect.appendChild(option);
                }
            });
        }

        function updateAdditionalFields() {

            const selectedType = typeSelect.value;

            document.getElementById('level-group').style.display =
                selectedType === 'course' ? '' : 'none';

            document.getElementById('duration-group').style.display =
                ['course', 'podcast'].includes(selectedType) ? '' : 'none';

            document.getElementById('pages-group').style.display =
                selectedType === 'book' ? '' : 'none';

            document.getElementById('read-minutes-group').style.display =
                ['book', 'article'].includes(selectedType) ? '' : 'none';
        }

        trackSelect.addEventListener('change', function() {
            updateTypes();
            updateCategories();
        });

        typeSelect.addEventListener('change', updateAdditionalFields);

        updateTypes(initialType);
        updateCategories(initialCategory);

    });
</script>

@endsection
