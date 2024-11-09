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

    public function __construct(
        protected $shoppingPlanId,
        protected $username,
        protected $message,
        protected $createdAt,
    ) {
    }

    public function broadcastOn()
    {
        return new Channel('channel_shopping_plan_'.$this->shoppingPlanId);
    }

    public function broadcastAs()
    {
        return 'ShoppingPlanCommentEvent';
    }

    public function broadcastWith()
    {
        return [
            'user_name'  => $this->username,
            'message'    => $this->message,
            'created_at' => $this->createdAt,
        ];
    }
}
