<?php

namespace App\Traits;
use Illuminate\Support\Str;


trait Message
{

    public function getPlainLink($encodedID, $type, $templateID){
        $baseUrl = env('APP_URL');
        if($type = 'sms')
            return $link = $baseUrl . '?key=' . $encodedID . '&sms=' . $templateID;
        else
            return $link = $baseUrl . '?key=' . $encodedID . '&email=' . $templateID;
    }

    /**
     * @param Object $object of arrays
     * @param string $text emailTemplate
     * @param string $shortenURL unique short URL
     * @param string $unsubscribeLink unique short URL for unsubscribe
     * @return string $text dynamic email template
     */
    
    public function replaceDynamicAttributes(Object $object, string $text, string $shortenURL = '', string $unsubscribeLink = ''): string
    {
        $object = (object)$object->getAttributes();
        foreach ($object as $key => $value) {
            if (!is_null($value) && !empty($value)) 
                $text = str_replace("[[" . $key . "]]", $value, $text);
        }
        if(!empty($shortenURL))
            $text = str_replace("[[LINK0]]", $shortenURL, $text);
        if(!empty($unsubscribeLink))
            $text = str_replace("[[unsubscribe]]", $unsubscribeLink, $text);
        return $text;
    }
}