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
        protected $id,
        protected $message,
        protected $createdBy,
        protected $createdAt,
        protected $userCreated,
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
            'id'            => $this->id,
            'message'       => $this->message,
            'user_created'  => $this->userCreated,
            'created_by'    => $this->createdBy,
            'created_at'    => $this->createdAt,
        ];
    }
}
