<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'name',
        'sms_template_id',
        'email_template_id',
        'datafrom',
        'days',
        'recursion',
        'start_time',
        'end_time',
        'hours',
        'iterations',
        'date',
        'time',
        'type',
    ];
    public function smsTemplate(){
        return $this->hasOne(SmsTemplate::class, 'id', 'sms_template_id');
    }
    public function emailTemplate(){
        return $this->hasOne(EmailTemplate::class, 'id', 'email_template_id');
    }


}
