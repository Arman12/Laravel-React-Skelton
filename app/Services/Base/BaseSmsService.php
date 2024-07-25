<?php

namespace App\Services\Base;

class BaseSmsService
{
    private $domain;
    private $apiKey;
    private $endpoint;
    private $senderId;
    private $campaignId;
    private $routeId;
    private $url;


    public function __construct()
    {
        $this->domain = env('SEVEN67_HOST');
        $this->apiKey = env('SEVEN67_API_KEY');
        $this->endpoint = "app/smsapi/index";
        $this->senderId = env('SEVEN67_SENDER_ID');
        $this->campaignId = env('SEVEN67_CAMPAIGN_ID');
        $this->routeId = env('SEVEN67_ROUTE_ID');
        $this->setUrl();

    }

    private function setUrl(){
        $this->url = $this->domain . $this->endpoint;
    }

    protected function getUrl(){
        return $this->url;
    }


    protected function formatPhone(string $phone_number) : string
    {
        if (substr($phone_number, 0, 2) === "07" && strlen($phone_number) === 11) {
            return "447" . substr($phone_number, 2);
        } else if (substr($phone_number, 0, 1) === "7" && strlen($phone_number) === 10) {
            return "447" . substr($phone_number, 1);
        } else if (substr($phone_number, 0, 3) === "447" && strlen($phone_number) === 12) {
            return $phone_number;
        } else if (substr($phone_number, 0, 4) === "+441" && strlen($phone_number) === 13) {
            return "441" . substr($phone_number, 4);
        } else if (substr($phone_number, 0, 4) === "0441" && strlen($phone_number) === 13) {
            return "441" . substr($phone_number, 4);
        } else if (substr($phone_number, 0, 5) === "00441" && strlen($phone_number) === 14) {
            return "441" . substr($phone_number, 5);
        } else if (substr($phone_number, 0, 2) === "01" && strlen($phone_number) === 11) {
            return "441" . substr($phone_number, 2);
        } else if (substr($phone_number, 0, 2) === "41" && strlen($phone_number) === 11) {
            return "441" . substr($phone_number, 2);
        } else if (substr($phone_number, 0, 1) === "1" && strlen($phone_number) === 10) {
            return "441" . substr($phone_number, 1);
        } else if (substr($phone_number, 0, 3) === "441" && strlen($phone_number) === 12) {
            return $phone_number;
        } else if (substr($phone_number, 0, 3) === "041" && strlen($phone_number) === 12) {
            return "441" . substr($phone_number, 3);
        } else if (substr($phone_number, 0, 4) === "+447" && strlen($phone_number) === 13) {
            return "447" . substr($phone_number, 4);
        } else if (substr($phone_number, 0, 4) === "0447" && strlen($phone_number) === 13) {
            return "447" . substr($phone_number, 4);
        } else if (substr($phone_number, 0, 5) === "00447" && strlen($phone_number) === 14) {
            return "447" . substr($phone_number, 5);
        } else if (substr($phone_number, 0, 2) === "47" && strlen($phone_number) === 11) {
            return "447" . substr($phone_number, 2);
        } else if (substr($phone_number, 0, 3) === "047" && strlen($phone_number) === 12) {
            return "447" . substr($phone_number, 3);
        }
    }

    private function setPostfields(){
        $data = array(
            'key' => $this->apiKey,
            'campaign' => $this->campaignId,
            'routeid' => $this->routeId,
            'type' => 'text',
            'contacts' => $contacts,
            'senderid' => $this->senderId,
            'msg' => $text
        );
    }

}

