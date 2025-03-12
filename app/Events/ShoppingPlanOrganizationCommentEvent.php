<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ShoppingPlanOrganizationCommentEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(protected $data)
    {
    }

    public function broadcastOn()
    {
        return new Channel('channel_shopping_plan_organization'.$this->data['target_id']);
    }

    public function broadcastAs()
    {
        return 'ShoppingPlanOrganizationCommentEvent';
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
