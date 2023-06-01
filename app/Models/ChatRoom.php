<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class ChatRoom extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'first_user',
        'second_user',
        'chat_group_id',
        'uniqd',
        'last_at'
    ];

    public function setCreatedAtAttribute($value): void
    {
        date_default_timezone_set("America/Asuncion");
        $this->attributes['created_at'] = Carbon::now();
    }

    public function setUpdateAtAttribute($value): void
    {
        date_default_timezone_set("America/Asuncion");
        $this->attributes['updated_at'] = Carbon::now();
    }

    public function setDeletedAtAttribute($value): void
    {
        date_default_timezone_set("America/Asuncion");
        $this->attributes['deleted_at'] = Carbon::now();
    }

    public function FirstUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_user');
    }

    public function SecondUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_user');
    }

    public function ChatGroup(): BelongsTo
    {
        return $this->belongsTo(ChatGroup::class, 'chat_group_id');
    }
    public function Chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'chat_room_id');
    }

    public function getLastMessageAttribute()
    {
        $chat = $this->Chats()
            ->sortByDesc('id')
            ->first();

        return $chat ?
            $chat->message ?
                $chat->message : 'Archivo Enviado' :
            NULL;
    }
    public function getLastMessageUserAttribute()
    {
        $chat = $this->Chats()
            ->sortByDesc('id')
            ->first();
        return $chat ? $chat->from_user_id : NULL;
    }
    public function getLastTimeCreateAtAttribute()
    {
        $chat = $this->Chats()
            ->sortByDesc('id')
            ->first();
        return $chat ? $chat->created_at->diffForHumans() : NULL;
    }

    public function getCountMessages($user): int
    {
        return $this->Chats()
            ->where('from_user_id','<>' , $user)
            ->where('read_at',NULL)
            ->count();
    }
}
