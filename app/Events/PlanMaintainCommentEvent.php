<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class PlanMaintainCommentEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(
        protected $data,
    ) {
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
        return [
            'id'            => $this->data['comment_id'],
            'message'       => $this->data['message'],
            'user_created'  => $this->data['user_name'],
            'created_by'    => $this->data['user_id'],
            'created_at'    => $this->data['time'],
        ];
    }
}
