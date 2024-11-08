<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    // Chỉ định kênh mà sự kiện sẽ được broadcast trên đó
    public function broadcastOn()
    {
        return new Channel('channel_test');  // Đảm bảo kênh là 'test'
    }

    // Đảm bảo rằng tên sự kiện phải là 'NewMessageEvent'
    public function broadcastAs()
    {
        return 'NewMessageEvent';
    }

    public function broadcastWith()
    {
        return ['message' => 'aaaaaaaaaa']; // Chuyển dữ liệu message vào
    }
}
