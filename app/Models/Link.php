<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    public $fillable = [
        'title', 'comment', 'url', 'url_status'
    ];

    public $appends = ['valid_tags'];

    /**
     * @return HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    /*
     * This allows us to easily filter on the frontend, any selected filters.
     * Here, we only want valid tags, as we are saving all tags from Pinboard
     */
    public function getValidTagsAttribute(): array
    {
        return $this->tags()->whereIn('tag', ['laravel','vue','vue.js','api','php'])->pluck('tag')->toArray();
    }
}
