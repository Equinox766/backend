<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatRoom;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('chat.room.{uniqd}', function ($user, $uniqd) {
    $chatroom = ChatRoom::where('uniqd', $uniqd)->first();
    if($chatroom->chat_group_id){
        return true;
    }else{
        return (int) $chatroom->id === (int) $user->first_user || (int) $chatroom->id === (int) $user->second_user;
    }
});

Broadcast::channel('chat.refresh.room.{id}', function($user, $id){
   return (int) $user->id === (int) $id;
});