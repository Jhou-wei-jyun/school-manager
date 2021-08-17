<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $newMessage;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($newMessage)
    {
        $this->newMessage = $newMessage;
        // $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('message-' . $this->newMessage->user_id);
        // return new PrivateChannel('message-' . $this->newMessage->user_id);
        // return new Channel('activities.' . $this->newMessage->user_id);
        // return new PresenceChannel('message-' . "67-4");
        // return new PresenceChannel('activities-1');
    }
    // public function broadcastWith() {
    //     return ['user_id' => $this->newMessage->user_id];
    // }
}
