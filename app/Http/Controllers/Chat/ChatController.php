<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ChatResource;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function listMyChat(): JsonResponse
    {
        $chatroom = ChatRoom::where('first_user', auth('api')->user()->id)
            ->orWhere('first_user', auth('api')->user()->id)
            ->orderBy('last_at', 'desc')
            ->get();
        return response()->json(
        [
            'chatroom' => $chatroom->map(function($item){
                return ChatResource::make($item);
            })
        ]);
    }

    public function sendMessageText(Request $request)
    {
        date_default_timezone_set('America/Asuncion');
        $request->request->add(['from_user_id' => auth('api')->user()->id]);
        $chat = Chat::create($request->all());

        $chat->ChatRoom->update(['last_at' => now()->format('Y-m-d H:i:s.u')]);
        //Notificar al segundo usuario y hacer un push de mensaje

        //Notificar a la sala de chat del usuario

        //Notificar a la sala de chat del segundo usuario
    }

    public function startChat(Request $request)
    {
        date_default_timezone_set('America/Asuncion');
        if ($request->to_user_id == auth('api')->user()->id) {
            return response()->json(['error' => 'No puedes iniciar un chat contigo mismo']);
        }
        $isExistRooms = ChatRoom::whereIn('first_user', [$request->to_user_id, auth('api')->user()->id])
            ->where('first_user', [auth('api')->user()->id, $request->to_user_id,])
            ->orderBy('last_at', 'desc')
            ->count();
        if ($isExistRooms > 0)
        {
            $chatroom = ChatRoom::where('first_user', auth('api')->user()->id)
                ->orWhere('first_user', auth('api')->user()->id)
                ->orderBy('last_at', 'desc')
                ->first();
            Chat::where('from_user_id', $request->to_user_id)
                ->where('chat_room_id', $chatroom->id)
                ->where(['read_at' => now()]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
