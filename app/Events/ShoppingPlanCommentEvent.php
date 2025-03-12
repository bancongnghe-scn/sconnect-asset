<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ShoppingPlanCommentEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(protected $data)
    {
    }

    public function broadcastOn()
    {
        return new Channel('channel_shopping_plan_'.$this->data['target_id']);
    }

    public function broadcastAs()
    {
        return 'ShoppingPlanCommentEvent';
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
