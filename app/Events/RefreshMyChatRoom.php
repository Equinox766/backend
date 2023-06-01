<?php

namespace App\Events;

use App\Models\ChatRoom;
use App\Http\Resources\ChatResource;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class RefreshMyChatRoom implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $to_user_id;
    public function __construct($to_user_id)
    {
        $this->to_user_id = $to_user_id;
    }

    public function broadcastWith(): array
    {
        $chatroom = ChatRoom::where('first_user', $this->to_user_id)
            ->orWhere('first_user', $this->to_user_id)
            ->orderBy('last_at', 'desc')
            ->get();
        return [
            'chatroom' => $chatroom->map(function($item){
                return ChatResource::make($item);
            })
        ];
    }
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.refresh.room.'.$this->$to_user_id);
    }
}
