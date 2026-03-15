<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsVersion extends Model
{
    protected $fillable = [
        'news_id', 'editor_id', 'title', 'summary', 
        'content', 'notes', 'version_number'
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
}
