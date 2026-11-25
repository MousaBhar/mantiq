<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Content extends Model
{
    protected $fillable = [
        'type',
        'track',
        'category_id',
        'created_by',
        'title',
        'description',
        'cover',
        'price',
        'status',
        'published_at',
        'is_featured',
        'views_count',
        'level',
        'pages',
        'read_minutes',
        'duration_minutes',
        'media_url',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'views_count' => 'integer',
            'pages' => 'integer',
            'read_minutes' => 'integer',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * Content category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * User who created the content.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Content lessons.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Additional article details.
     */
    public function articleDetail(): HasOne
    {
        return $this->hasOne(ArticleDetail::class);
    }

    /**
     * Users enrolled in this course.
     */
    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }
}
