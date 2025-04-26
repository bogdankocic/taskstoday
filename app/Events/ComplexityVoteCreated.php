<?php

namespace App\Events;

use App\Models\TaskComplexityVote;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplexityVoteCreated
{
    use Dispatchable, SerializesModels;

    public TaskComplexityVote $vote;

    /**
     * Create a new event instance.
     */
    public function __construct(TaskComplexityVote $vote)
    {
        $this->vote = $vote;
    }
}
