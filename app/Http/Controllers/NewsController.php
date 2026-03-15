<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        $news = News::with(['category', 'author', 'tags'])->latest()->paginate(15);
        return Response::json($news);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'nullable|string|in:urgent,high,normal,low',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $news = $this->newsService->createNews($validated);

        return Response::json([
            'message' => 'News created successfully',
            'data' => $news->load(['category', 'tags'])
        ], 201);
    }

    public function show(News $news)
    {
        return Response::json($news->load(['category', 'author', 'tags', 'versions', 'workflows']));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'summary' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'nullable|string|in:urgent,high,normal,low',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'edit_notes' => 'nullable|string'
        ]);

        $updatedNews = $this->newsService->updateNews($news, $validated);

        return Response::json([
            'message' => 'News updated successfully',
            'data' => $updatedNews->load(['category', 'tags'])
        ]);
    }

    public function destroy(News $news)
    {
        $news->delete();
        return Response::json(['message' => 'News deleted successfully']);
    }
}
