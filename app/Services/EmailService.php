<?php

namespace App\Services;

use App\Services\Base\BaseEmailService;
use App\Services\AwsService;
use App\Traits\Encryption;
use Illuminate\Support\Facades\Storage;

class EmailService extends BaseEmailService
{

    use Encryption;

    /**
     * Returns the message id in case of successfull email sending and full response of mailgun, used to send emails
     * @return array $response
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */
    public function sendEmail(): array
    {
        try {
            $mailgunResponse = $this->setCurl();
            $mailgunResponseArray = json_decode($mailgunResponse, true);
            if (isset($mailgunResponseArray['id'])) {
                $msgId = $mailgunResponseArray['id'];
                $response['msg_id'] = str_replace("<", "", str_replace(">", "", $msgId));
            }
            $response['response'] = $mailgunResponse;
            return $response;
        } catch (\Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }

    /**
     * Returns the message id in case of successfull email sending and full response of mailgun, used to send emails with attachement
     * @param string $attachmentPath path of attachement if there is any
     * @return array $response which includes msg_id and full response
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */
    public function sendEmailWithAttachment($attachmentPath): array
    {
        try {
            $mailgunResponse = $this->setCurl();
            $mailgunResponseArray = json_decode($mailgunResponse, true);
            if (isset($mailgunResponseArray['id'])) {
                $msgId = $mailgunResponseArray['id'];
                $response['msg_id'] = str_replace("<", "", str_replace(">", "", $msgId));
            }
            $response['response'] = $mailgunResponse;
            return $response;
        } catch (\Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }

    /**
     * Returns the status of email address to verify is email will be deliverable and what is the status of delivery.
     * @return string $response
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */
    public function validateEmail(): string
    {
        try {
            $response = $this->setCurlForValidateEmail();
            return $response;
        } catch (\Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }

    /**
     * Returns the EML file against the provided email message ID
     * @return string $response Save EML file to AWS and returns the aws Bucket link
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */
    public function generateEmlFile()
    {
        $msgId = $this->meesageId;
        if ($msgId == "") {
            $msgId = 'myeml_' . date('YmdHisu') . rand(1000, 9999);
        }
        $jo = $this->getEmlPayload();
        if ($msgId == "") {
            $msgId = 'myeml_' . date('YmdHisu') . rand(1000, 9999);
        }
        $kw = preg_replace('/[^A-Za-z0-9\-]/', '', $msgId);
        //Warning: Please don't try to induntate below template text
        $template =
            'Message-ID: [Message-Id]
Date: [Date]
Subject: [Subject]
From: [From]
Reply-To: 
To: [To]
MIME-Version: 1.0
Content-Type: multipart/alternative; boundary="' . $kw . '"

--' . $kw . '
Content-Type: text/plain; charset="UTF-8"

[body-plain]

--' . $kw . '
Content-Type: text/html; charset="UTF-8"

[body-html]

--' . $kw . '--';

        foreach ($jo as $k => $j) {
            if (!is_array($j)) {
                $template = str_replace("[" . $k . "]", $j, $template);
            }
        }
        $path = "emls/{$kw}.eml";
        Storage::disk('public')->put($path, $template);
        $awsService = new AwsService;
        $awsFileLink = $awsService->saveToAWS($path);
        Storage::delete($path);
        return $awsFileLink;
    }
}
