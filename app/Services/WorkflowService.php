<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsWorkflow;
use Illuminate\Support\Facades\Auth;
use Exception;

class WorkflowService
{
    /**
     * Define allowed transitions
     */
    protected array $transitions = [
        'draft' => ['submitted'],
        'submitted' => ['under_review', 'draft'],
        'under_review' => ['edited', 'submitted'],
        'edited' => ['approved', 'under_review'],
        'approved' => ['scheduled', 'published', 'edited'],
        'scheduled' => ['published', 'approved'],
        'published' => ['archived'],
        'archived' => ['draft'], // Capability to republish or restart
    ];

    /**
     * Transition news to a new status
     */
    public function transitionTo(News $news, string $targetStatus, ?string $reason = null): News
    {
        $currentStatus = $news->status;

        if (!$this->canTransition($currentStatus, $targetStatus)) {
            throw new Exception("Transition from {$currentStatus} to {$targetStatus} is not allowed.");
        }

        // Log the workflow movement
        NewsWorkflow::create([
            'news_id' => $news->id,
            'from_status' => $currentStatus,
            'to_status' => $targetStatus,
            'user_id' => Auth::id(),
            'reason' => $reason,
        ]);

        // Update news status
        $news->update(['status' => $targetStatus]);

        if ($targetStatus === 'published' && !$news->published_at) {
            $news->update(['published_at' => now()]);
        }

        return $news;
    }

    /**
     * Check if a transition is valid
     */
    public function canTransition(string $currentStatus, string $targetStatus): bool
    {
        return isset($this->transitions[$currentStatus]) && 
               in_array($targetStatus, $this->transitions[$currentStatus]);
    }
}
