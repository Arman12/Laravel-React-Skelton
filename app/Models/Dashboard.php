<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;
    protected $table = 'primary_leads';

    protected $fillable = [
        'title', 'firstname', 'lastname', 'leadip', 'browser', 'device', 'phone', 'email', 'PostURL', 'is_completed','address_line1','address_line2','address_line3','city','country','postcode','dob_day','dob_month','dob_day'
    ];
}
