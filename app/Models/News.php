<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model implements HasMedia
{
    use InteractsWithMedia, Searchable, SoftDeletes;

    protected $fillable = [
        'uuid', 'user_id', 'category_id', 'title', 'slug', 'summary', 
        'content', 'status', 'priority', 'published_at', 'archived_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'news_tags');
    }

    public function versions()
    {
        return $this->hasMany(NewsVersion::class);
    }

    public function workflows()
    {
        return $this->hasMany(NewsWorkflow::class);
    }
}
