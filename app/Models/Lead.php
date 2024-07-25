<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const STATUS_PARTIAL   = 0;
    public const STATUS_COMPLETED   = 1;
    public const STATUS_DOCUMENTED   = 2;
    public const STATUS_EMAILED   = 3;
    public const STATUS_SUBMITTED   = 4;
    public const EMAILED_FAILED   = 9;
    public const TERMS_TRUE   = 1;
    public const TERMS_FALSE   = 0;
    use HasFactory;
    protected $fillable = [
        'title', 'first_name', 'last_name', 'email', 'phone', 'address', 'date_of_birth', 'signature_src', 'agree_terms', 'tax_year', 'tax_payer', 'status', 'ip_address', 'device', 'browser', 'document_url', 'audit_pdf_url', 'eml_file_url', 'document_date_time', 'signature_date_time', 'counter'
    ];
    protected $hidden =['id'];
}
