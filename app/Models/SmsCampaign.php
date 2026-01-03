<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    protected $fillable = [
        'name',
        'message',
        'recipient_type',
        'custom_recipients',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'sent_count',
        'failed_count',
        'delivered_count',
        'cost',
    ];

    protected $casts = [
        'custom_recipients' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(SmsMessage::class, 'campaign_id');
    }

    public function getSuccessRateAttribute()
    {
        if ($this->sent_count == 0) return 0;
        return round(($this->delivered_count / $this->sent_count) * 100, 2);
    }
}
