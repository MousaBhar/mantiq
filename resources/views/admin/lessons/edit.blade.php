@extends('layouts.app')

@section('content')
<div class="lesson-page">

    <div class="lesson-header">
        <div>
            <h1>Edit Lesson</h1>
            <p>Update lesson details for: <strong>{{ $content->title }}</strong></p>
        </div>

        <a href="{{ route('admin.contents.lessons.index', $content) }}"
           class="back-button">
            Back to Lessons
        </a>
    </div>

    <div class="lesson-form-card">

        <form action="{{ route('admin.contents.lessons.update', [$content, $lesson]) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Lesson Title <span>*</span></label>

                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title', $lesson->title) }}"
                       placeholder="Enter lesson title"
                       required>

                @error('title')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>

                <textarea id="description"
                          name="description"
                          rows="5"
                          placeholder="Enter lesson description">{{ old('description', $lesson->description) }}</textarea>

                @error('description')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="sort_order">Lesson Order <span>*</span></label>

                    <input type="number"
                           id="sort_order"
                           name="sort_order"
                           min="0"
                           value="{{ old('sort_order', $lesson->sort_order) }}"
                           required>

                    @error('sort_order')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="duration_minutes">Duration (Minutes)</label>

                    <input type="number"
                           id="duration_minutes"
                           name="duration_minutes"
                           min="1"
                           value="{{ old('duration_minutes', $lesson->duration_minutes) }}"
                           placeholder="e.g. 30">

                    @error('duration_minutes')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="form-group">
                <label for="media_url">Media URL</label>

                <input type="url"
                       id="media_url"
                       name="media_url"
                       value="{{ old('media_url', $lesson->media_url) }}"
                       placeholder="https://example.com/video">

                @error('media_url')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">

                <a href="{{ route('admin.contents.lessons.index', $content) }}"
                   class="cancel-button">
                    Cancel
                </a>

                <button type="submit" class="submit-button">
                    Update Lesson
                </button>

            </div>

        </form>
    </div>
</div>

<style>
.lesson-page {
    max-width: 950px;
    margin: 35px auto;
    padding: 0 20px;
    font-family: Arial, sans-serif;
}

.lesson-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.lesson-header h1 {
    color: #172033;
    margin: 0 0 8px;
}

.lesson-header p {
    color: #718096;
    margin: 0;
}

.lesson-form-card {
    background: white;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 3px 15px rgba(0,0,0,.05);
}

.form-group {
    margin-bottom: 22px;
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 9px;
    color: #344054;
    font-weight: 600;
}

.form-group label span {
    color: #e04444;
}

.form-group input,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 12px 14px;
    border: 1px solid #dce2ea;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #4169e1;
    box-shadow: 0 0 0 3px rgba(65,105,225,.1);
}

.form-group textarea {
    resize: vertical;
}

.form-row {
    display: flex;
    gap: 20px;
}

.error-message {
    display: block;
    color: #d64545;
    margin-top: 7px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 10px;
    border-top: 1px solid #edf0f5;
}

.submit-button,
.cancel-button,
.back-button {
    padding: 11px 20px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
}

.submit-button {
    background: #4169e1;
    color: white;
}

.cancel-button,
.back-button {
    background: #edf1f7;
    color: #344054;
}

@media (max-width: 600px) {
    .lesson-header,
    .form-row {
        flex-direction: column;
        align-items: stretch;
    }

    .lesson-form-card {
        padding: 20px;
    }
}
</style>
@endsection
