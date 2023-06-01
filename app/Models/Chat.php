<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'from_user_id',
        'chat_room_id',
        'chat_group_id',
        'message',
        'chat_file_id',
        'read_at'
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

    public function FromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    public function ChatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }
    public function ChatGroup(): BelongsTo
    {
        return $this->belongsTo(ChatGroup::class, 'chat_group_id');
    }
    public function ChatFile(): BelongsTo
    {
        return $this->belongsTo(ChatFile::class, 'chat_file_id');
    }
}
