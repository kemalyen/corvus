<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EventRegistration extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\RegistrationFactory> */
    use HasFactory;
 use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'is_attending',
        'registered_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'is_attending' => 'boolean',
        'event_id' => 'integer',
        'email' => 'string',
        'phone' => 'string',
        'notes' => 'string',
        'name' => 'string',
        'event' => Event::class,
        'status' => RegistrationStatus::class,
    ];
 
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
