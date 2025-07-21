<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'start_time' => 'datetime:Y-m-d H:i',
        'is_public' => 'boolean',
        'status' => EventStatus::class,
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }   

    protected function status_value(): Attribute
    {
        return Attribute::make(
            get: fn($value) => EventStatus::tryFrom($value)
        );
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('Y-m-d H:i');
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
