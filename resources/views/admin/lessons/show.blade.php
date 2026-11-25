@extends('layouts.app')

@section('content')
<div class="lesson-page">

    <div class="lesson-header">
        <div>
            <h1>Lesson Details</h1>
            <p>Viewing lesson information for: <strong>{{ $content->title }}</strong></p>
        </div>

        <a href="{{ route('admin.contents.lessons.index', $content) }}"
           class="back-button">
            Back to Lessons
        </a>
    </div>

    <div class="lesson-details-card">

        <div class="details-heading">
            <div>
                <span class="details-label">LESSON TITLE</span>
                <h2>{{ $lesson->title }}</h2>
            </div>

            <a href="{{ route('admin.contents.lessons.edit', [$content, $lesson]) }}"
               class="edit-button">
                Edit Lesson
            </a>
        </div>

        <div class="details-grid">

            <div class="detail-item">
                <span class="detail-label">Course</span>
                <strong>{{ $content->title }}</strong>
            </div>

            <div class="detail-item">
                <span class="detail-label">Lesson ID</span>
                <strong>#{{ $lesson->id }}</strong>
            </div>

            <div class="detail-item">
                <span class="detail-label">Lesson Order</span>
                <strong>{{ $lesson->sort_order }}</strong>
            </div>

            <div class="detail-item">
                <span class="detail-label">Duration</span>
                <strong>
                    {{ $lesson->duration_minutes
                        ? $lesson->duration_minutes . ' minutes'
                        : 'Not specified' }}
                </strong>
            </div>

            <div class="detail-item">
                <span class="detail-label">Created At</span>
                <strong>{{ $lesson->created_at->format('d M Y, h:i A') }}</strong>
            </div>

            <div class="detail-item">
                <span class="detail-label">Last Updated</span>
                <strong>{{ $lesson->updated_at->format('d M Y, h:i A') }}</strong>
            </div>

        </div>

        <div class="description-section">
            <span class="detail-label">DESCRIPTION</span>

            <p>
                {{ $lesson->description ?: 'No description available.' }}
            </p>
        </div>

        <div class="media-section">
            <span class="detail-label">LESSON MEDIA</span>

            @if($lesson->media_url)
                <a href="{{ $lesson->media_url }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="media-link">
                    Open Lesson Media
                </a>
            @else
                <p>No media URL has been added.</p>
            @endif
        </div>

    </div>
</div>

<style>
.lesson-page {
    max-width: 1000px;
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

.lesson-details-card {
    background: white;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 3px 15px rgba(0,0,0,.05);
}

.details-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding-bottom: 25px;
    border-bottom: 1px solid #edf0f5;
}

.details-heading h2 {
    color: #172033;
    margin: 8px 0 0;
}

.details-label,
.detail-label {
    display: block;
    color: #98a2b3;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 9px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 25px;
    padding: 28px 0;
}

.detail-item strong {
    display: block;
    color: #344054;
    font-size: 14px;
}

.description-section,
.media-section {
    padding: 22px 0;
    border-top: 1px solid #edf0f5;
}

.description-section p,
.media-section p {
    color: #475467;
    line-height: 1.8;
    margin: 0;
    white-space: pre-line;
}

.edit-button,
.back-button {
    padding: 11px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.edit-button {
    background: #4169e1;
    color: white;
}

.back-button {
    background: #edf1f7;
    color: #344054;
}

.media-link {
    display: inline-block;
    color: #4169e1;
    text-decoration: none;
    font-weight: 600;
}

@media (max-width: 600px) {
    .lesson-header,
    .details-heading {
        flex-direction: column;
        align-items: flex-start;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .lesson-details-card {
        padding: 20px;
    }
}
</style>
@endsection
