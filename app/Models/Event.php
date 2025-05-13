<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_of_event',
        'event_name',
        'address',
        'address_location',
        'is_adv_paid',
        'adv_amt',
        'is_ret_paid',
        'ret_amt',
        'misc_spend',
        'org_id',
        'added_by',
        'updated_by',
        'contact_person_name',
        'contact_person_phone',
    ];

    protected $casts = [
        'misc_spend' => 'array',
    ];

    public function org()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
