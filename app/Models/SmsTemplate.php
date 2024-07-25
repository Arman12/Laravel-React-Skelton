<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;
    protected $table = 'sms_templates';
    protected $fillable = [
        'status',
        'title',
        'description',
        'subject',
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class, 'id', 'sms_template_id');
    }
}
