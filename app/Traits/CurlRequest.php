<?php

namespace App\Traits;

trait CurlRequest
{
    public function sendGetRequest($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    public function sendPostRequest(
        string $url,
        array|string $data,
        $apiKey = '',
        array $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ]
    ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if ($apiKey) {
            curl_setopt($ch, CURLOPT_USERPWD, "$apiKey");
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
