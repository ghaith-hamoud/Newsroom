<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NewsService
{
    /**
     * Create a new news item with initial versioning
     */
    public function createNews(array $data): News
    {
        return DB::transaction(function () use ($data) {
            $news = News::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => Auth::id(),
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'summary' => $data['summary'] ?? null,
                'content' => $data['content'],
                'status' => 'draft',
                'priority' => $data['priority'] ?? 'normal',
            ]);

            $this->createVersion($news);

            if (isset($data['tags'])) {
                $news->tags()->sync($data['tags']);
            }

            return $news;
        });
    }

    /**
     * Update news and create a new version record
     */
    public function updateNews(News $news, array $data): News
    {
        return DB::transaction(function () use ($news, $data) {
            $news->update($data);
            
            if (isset($data['tags'])) {
                $news->tags()->sync($data['tags']);
            }

            $this->createVersion($news, $data['edit_notes'] ?? null);

            return $news;
        });
    }

    /**
     * Helper to create a version snapshot
     */
    protected function createVersion(News $news, ?string $notes = null): NewsVersion
    {
        $lastVersion = $news->versions()->max('version_number') ?? 0;

        return NewsVersion::create([
            'news_id' => $news->id,
            'editor_id' => Auth::id(),
            'title' => $news->title,
            'summary' => $news->summary,
            'content' => $news->content,
            'notes' => $notes,
            'version_number' => $lastVersion + 1,
        ]);
    }
}
