<?php

namespace App\Services\Base;

use App\Traits\CurlRequest;

class BaseEmailService
{
    use CurlRequest;

    private $apiKey;
    private $domain;
    private $from;
    protected $ch;
    private $url;
    private $validateUrl;
    private $to;
    private $subject = '';
    private $message;
    protected $postData;
    protected $meesageId;


    /**
     * BaseEmailService constructor
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */

    public function __construct()
    {
        $this->apiKey = env('MAILGUN_API_KEY');
        $this->domain = env('MAILGUN_DOMAIN');
        $this->from = env('MAILGUN_FROM');

        $this->url = "https://api.eu.mailgun.net/v3/$this->domain/messages";
        $this->validateUrl  = "https://api.mailgun.net/v4/address/validate";
        $this->ch = curl_init();
    }

    /**
     * method to set sendTo
     * @param string $sendTo
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    public function to($sendTo)
    {
        $this->to = $sendTo;
        return $this;
    }
    /**
     * method to set emailSubject
     * @param string $emailSubject
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    public function subject($emailSubject = '')
    {
        $this->subject = $emailSubject;
        return $this;
    }
    /**
     * method to set message
     * @param string $emailMessage
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    public function message($emailMessage)
    {
        $this->message = $emailMessage;
        return $this;
    }
    /**
     * method to set meesageId
     * @param string $messageId
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    public function meesageId($meesageId)
    {
        $this->meesageId = $meesageId;
        return $this;
    }
    /**
     * To set the CURL request
     * Set CURL request
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    protected function setCurl($attachment = '')
    {
        $this->postData = array(
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'html' => $this->message,
        );
        $attachmentPath = $attachment;
        if ($attachmentPath) {
            if (file_exists(public_path($attachmentPath))) {
                $this->postData['attachment'] = curl_file_create(public_path($attachmentPath));
            }
        }
        $res = $this->sendPostRequest($this->url, $this->postData, 'api:' . $this->apiKey);
        return $res;
    }

    /**
     * to set the curl for email validation
     * Set CURL request to validate email
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    protected function setCurlForValidateEmail()
    {
        $this->postData = array(
            'address' => $this->to,
        );
        $res = $this->sendPostRequest($this->validateUrl, $this->postData, 'api:' . $this->apiKey);
        return $res;
    }

    /**
     * Get payload for eml file generation
     * @return $emlArray
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    protected function getEmlPayload(): array
    {
        $date = date('d/m/Y h:i:s a');
        $emlArray = array(
            'Mime-Version' => '1.0',
            'Date' => $date,
            'Message-Id' => '<' . $this->meesageId . '>',
            'Content-Type' => 'text/html; charset="ascii"',
            'Subject' => $this->subject,
            'From' => env('MAILGUN_FROM'),
            'To' => $this->to,
            'body-plain' => strip_tags($this->message),
            'body-html' => $this->message
        );
        return $emlArray;
    }
}
