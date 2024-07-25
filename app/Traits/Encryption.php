<?php

namespace App\Traits;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;
//specific for id encryption
use Hashids\Hashids;

trait Encryption
{
    /**
     * encryptString to encrypt string
     * @param  string  $input
     * @return string
    */
    
    public function encryptString($input = ''): string
    {
        return Crypt::encryptString($input);
    }
    
    /**
     * decryptString to decrypt string
     * @param  string  $encryptedValue
     * @return string
    */
    
    public function decryptString($encryptedValue = ''): string
    {
        try {
            return Crypt::decryptString($encryptedValue);
        } catch (DecryptException $e) {
            return '';
        }
    }

    /**
     * encryptId to encrypt id
     * @param  string  $id
     * @return string
    */
    
    public function encryptId($id = ''): string
    {
        $hashids = new Hashids(env('APP_SALT'), 7);
        return $hashids->encode($id);
    }
    
    /**
     * decryptId to decrypt id
     * @param  string  $encryptedId
     * @return string
    */
    
    public function decryptId($encryptedId = ''): string
    {
        $hashids = new Hashids(env('APP_SALT'), 7);
        $decode = $hashids->decode($encryptedId);
        if (isset($decode[0])) {
            return $decode[0];
        }
        return '';
    }

     /**
     * getRandomString
     * 
     * @return string
    */
    
    public function getRandomString(): string
    {
        return Str::random(16);
    }
}