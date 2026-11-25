<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'track',
        'sort_order',
        'description',
        'image',
        'is_active',
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
