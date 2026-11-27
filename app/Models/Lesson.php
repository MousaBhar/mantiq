<?php

namespace App\Models;

use App\Support\VideoEmbed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lesson extends Model
{
    protected $fillable = [
        'content_id',
        'title',
        'description',
        'body',
        'sort_order',
        'media_url',
        'video_url',
        'is_preview',
        'duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'duration_minutes' => 'integer',
            'is_preview' => 'boolean',
        ];
    }

    /**
     * Embeddable player URL for the lesson video (YouTube / Vimeo only).
     */
    public function getEmbedUrlAttribute(): ?string
    {
        return VideoEmbed::toEmbedUrl($this->video_url);
    }

    /**
     * Lesson body rendered from Markdown (raw HTML is stripped).
     */
    public function getBodyHtmlAttribute(): ?string
    {
        if (blank($this->body)) {
            return null;
        }

        return Str::markdown($this->body, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * The content this lesson belongs to.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * Progress records for this lesson.
     */
    public function progressRecords(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }
}
