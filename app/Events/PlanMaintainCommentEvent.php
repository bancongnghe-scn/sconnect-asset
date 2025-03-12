<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class PlanMaintainCommentEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(
        protected $data,
    ) {
        Log::info($this->data);
    }

    public function broadcastOn()
    {
        return new Channel('channel_plan_maintain_'.$this->data['target_id']);
    }

    public function broadcastAs()
    {
        return 'PlanMaintainCommentEvent';
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
