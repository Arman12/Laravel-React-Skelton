<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $table = 'email_templates';
    protected $fillable = [
        'status',
        'title',
        'description',
        'subject',
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class, 'id', 'email_template_id');
    }
}
