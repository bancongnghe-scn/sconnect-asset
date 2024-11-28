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
        return new Channel('channel_shopping_plan_organization'.$this->shoppingPlanId);
    }

    public function broadcastAs()
    {
        return 'ShoppingPlanOrganizationCommentEvent';
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
