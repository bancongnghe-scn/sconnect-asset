<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class OrderCommentEvent implements ShouldBroadcast
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
        return new Channel('channel_order'.$this->data['target_id']);
    }

    public function broadcastAs()
    {
        return 'OrderCommentEvent';
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
