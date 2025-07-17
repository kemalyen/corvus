<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'location',
        'organizer',
        'capacity',
        'is_public',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'is_public' => 'boolean',
    ];

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function getPublicStatusAttribute()
    {
        return $this->is_public ? 'Public' : 'Private';
    }

    public function getStartTimeFormattedAttribute()
    {
        return $this->start_time->format('d M Y H:i');
    }
}
