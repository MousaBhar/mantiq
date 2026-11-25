<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'content_id',
        'title',
        'description',
        'sort_order',
        'media_url',
        'duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'duration_minutes' => 'integer',
        ];
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
