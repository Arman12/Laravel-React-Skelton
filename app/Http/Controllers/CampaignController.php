<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;


class CampaignController extends Controller
{

    private SmsService $smsService;
    /**
     * Data8Controller constructor.
     *
     * @param \App\Services\Data8Service $service
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendSms(){
        dd($this->smsService->send());
    }
}