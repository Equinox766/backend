<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'uniqd',
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

    public function Chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'chat_group_id');
    }

    public function ChatRooms(): HasMany
    {
        return $this->hasMany(ChatRoom::class, 'chat_group_id');
    }
}