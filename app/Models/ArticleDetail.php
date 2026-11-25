<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleDetail extends Model
{
    protected $fillable = [
        'content_id',
        'body',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
