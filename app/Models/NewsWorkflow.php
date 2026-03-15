<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsWorkflow extends Model
{
    protected $fillable = [
        'news_id', 'from_status', 'to_status', 
        'user_id', 'reason', 'duration_in_status'
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
