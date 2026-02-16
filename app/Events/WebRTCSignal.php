<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebRTCSignal implements ShouldBroadcast
{
    public $attemptId;
    public $type;
    public $payload;

    public function __construct($attemptId, $type, $payload)
    {
        $this->attemptId = $attemptId;
        $this->type = $type;
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return new Channel('exam-attempt.' . $this->attemptId);
    }
}