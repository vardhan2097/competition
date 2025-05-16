<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'changes',
        'event_created_at',
        'updated_by',
        'action',
    ];

    protected $casts = [
        'changes' => 'array',
        'event_created_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
