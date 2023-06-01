<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat;
use App\Models\User;
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

    public function sendMessageText(Request $request): void
    {
        date_default_timezone_set('America/Asuncion');
        $request->request->add(['from_user_id' => auth('api')->user()->id]);
        $chat = Chat::create($request->all());

        $chat->ChatRoom->update(['last_at' => now()->format('Y-m-d H:i:s.u')]);
        //Notificar al segundo usuario y hacer un push de mensaje

        //Notificar a la sala de chat del usuario

        //Notificar a la sala de chat del segundo usuario
    }

    public function startChat(Request $request): JsonResponse
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
            $chatroom = ChatRoom::whereIn('first_user', [$request->to_user_id, auth('api')->user()->id])
                ->where('first_user', [auth('api')->user()->id, $request->to_user_id,])
                ->orderBy('last_at', 'desc')
                ->first();
            Chat::where('from_user_id', $request->to_user_id)
                ->where('chat_room_id', $chatroom->id)
                ->where('read_at', NULL)
                ->where(['read_at' => now()]);
            $chats = Chat::where('chat_room_id', $chatroom->id)->orderBy('created_at', 'desc')->paginate(10);

            $data = [];

            $data['room_id'] = $chatroom->id;
            $data['chat_uniqd'] = $chatroom->uniqd;
            $to_user = User::find($request->to_user_id);
            $data['user'] = [
                'id'        => $to_user->id,
                'full_name' => $to_user->name.' '.$to_user->surname,
                'avatar'    => $to_user->avatar ? env('APP_URL').'storage/'.$to_user->avatar : NULL,
            ];
            if (count($chats) > 0){
                foreach ($chats as $key => $chat) {
                    $data['messages'][] = [
                        'id'         => $chat->id,
                        'sender'     => [
                            'id'       => $chat->FromUser->id,
                            'fullname' => $chat->FromUser->name.' '.$chat->FromUser->surname,
                            'avatar'   => $chat->FromUser->avatar ? env('APP_URL').'storage/'.$chat->FromUser->avatar : NULL,
                        ],
                        'message'    => $chat->message,
                        //Files
                        'read_at'    => $chat->read_at,
                        'time'       => $chat->created_at->diffForHumans(),
                        'created_at' => $chat->created_at,
                    ];
                }
            }else{
                $data['messages'][] = [];
            }
            $data['exist']     = 1;
            $data['last_page'] = $chats->last_page();
            return response()->json($data);
        } else {
            $chatroom = ChatRoom::create([
                'first_user'  => auth()->user()->id,
                'second_user' => $request->to_user_id,
                'last_at'     => now()->format('Y-m-d H:i:s.u'),
                'uniqd'       => uniqid(),
            ]);
            $data['messages'][] = [];
            $data['exist']      = 0;
            $data['last_page']  = 1;
            return response()->json($data);
        }

    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
