<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\News;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WorkflowController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function transition(Request $request, News $news)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        try {
            $updatedNews = $this->workflowService->transitionTo(
                $news, 
                $validated['status'], 
                $validated['reason']
            );

            return Response::json([
                'message' => "News status updated to {$validated['status']}",
                'data' => $updatedNews
            ]);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 422);
        }
    }
}
