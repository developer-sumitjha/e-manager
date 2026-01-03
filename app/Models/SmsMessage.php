<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    protected $fillable = [
        'campaign_id',
        'tenant_id',
        'phone_number',
        'message',
        'type',
        'status',
        'provider_message_id',
        'error_message',
        'sent_at',
        'delivered_at',
        'cost',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(SmsCampaign::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
