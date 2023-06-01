<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatFile extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    protected $fillable = [
        'file_names',
        'type',
        'resolution',
        'size',
        'uniqd',
        'file'
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

    public function FromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function getSizeAttribute($size): string
    {
        $size = (int)$size;
        $base = log($size)/ log(1024);
        $suffixes = array('bytes', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), 2) . $suffixes[floor($base)];
    }

    public function getNameFileAttribute()
    {
        $name = str_replace(' ', '-', $this->file_names);
        $newname = str_replace('_', '-', $name);
        return $newname;
    }
}
