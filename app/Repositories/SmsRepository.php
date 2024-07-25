<?php

namespace App\Repositories;
use App\Models\SmsTemplate;

/**
 * Class SmsRepository.
 */
class SmsRepository extends BaseRepository
{

    public function __construct(SmsTemplate $smsTemplate)
    {
        parent::__construct($smsTemplate);
    }

}
