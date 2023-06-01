<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SendMessageChat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    public function broadcastWith()
    {
        return [
            'id'         => $this->chat->id,
            'sender'     => [
                'id'       => $this->chat->FromUser->id,
                'fullname' => $this->chat->FromUser->name.' '.$this->chat->FromUser->surname,
                'avatar'   => $this->chat->FromUser->avatar ? env('APP_URL').'storage/'.$this->chat->FromUser->avatar : NULL,
            ],
            'message'    => $this->chat->message,
            //Files
            'read_at'    => $this->chat->read_at,
            'time'       => $this->chat->created_at->diffForHumans(),
            'created_at' => $this->chat->created_at,
        ];
    }
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.room.'.$this->chat->ChatRoom->uniqd);
    }
}
