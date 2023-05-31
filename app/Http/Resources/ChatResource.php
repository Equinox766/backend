<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "friend_first"  => $this->friend_first != auth('api')->user()->id ?[
                'id'          => $this->FirstUser->id,
                'fullName'    => $this->FirstUser->name.' '.$this->FirstUser->surnmae,
                'avatar'      => $this->FirstUser->avatar ? env("APP_URL")."storage/".$this->FirstUser->avatar : NULL,
            ] : NULL,
            "friend_second" =>  $this->second_first ?
                $this->second_first != auth('api')->user()->id ?
                    [
                        'id'       => $this->SecondUser->id,
                        'fullName' => $this->SecondUser->name.' '.$this->SecondUser->surnmae,
                        'avatar'   => $this->SecondUser->avatar ? env("APP_URL")."storage/".$this->SecondUser->avatar : NULL,
                    ]:NULL
                : NULL,
            "group_chat" => $this->chat_group_id ? [
                "id"     => $this->ChatGroup->id,
                "name"   => $this->ChatGroup->name,
                "avatar" => NULL,

                "last_message"       => $this->ChatGroup->last_message,
                "last_message_is_my" => $this->ChatGroup->last_message_user ? $this->last_message_user == auth('api')->user()->id : NULL,
                "last_time"          => $this->ChatGroup->last_time_created_at,
                "count_message"      => $this->ChatGroup->getCountMessage(auth('api')->user()->id),

            ] : NULL,
            "uniqd"              => $this->uniqd,
            "is_active"          => false,
            "last_message"       => $this->last_message,
            "last_message_is_my" => $this->last_message_user ? $this->last_message_user == auth('api')->user()->id : NULL,
            "last_time"          => $this->last_time_created_at,
            "count_message"      => $this->getCountMessage(auth('api')->user()->id),
        ];
    }
}
