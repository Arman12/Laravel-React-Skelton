<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Traits\Encryption;
use GuzzleHttp\Psr7\Stream;
use Aws\S3\S3Client;


class AwsService
{
    use Encryption;

    /**
     * @param  string  $path
     * @param  bool  $isSignature pass true if upload signature to AWS
     * @return string
     */
    public function saveToAWS(string $path, bool $isSignature = false): string
    {
        $awsKey = base64_decode(base64_decode(base64_decode($this->decryptString(env('AWS_KEY')))));
        $awsSecretKey = base64_decode(base64_decode(base64_decode($this->decryptString(env('AWS_SECRET_KEY')))));

        $s3Client = new S3Client([
            'version' => '2006-03-01',
            'region' => 'eu-west-2',
            'credentials' => ['key' => $awsKey, 'secret' => $awsSecretKey]
        ]);

        try {

            if ($isSignature === true) {
                $kf = 'user_sign_' . $this->getRandomString() . '_' . time() . '.png';
                $fileStream = $path;
                $contentType = null;
            } else {
                $key = explode('/', $path);
                $kf = end($key);
                // $absolutePath = storage_path("app/public/{$path}"); 
                $absolutePath = Storage::path('public/'.$path);// Full absolute path
                // Create a stream from the file contents
                $fileStream = new Stream(fopen($absolutePath, 'r'));

                // Determine the content type based on the file extension
                $contentType = pathinfo($absolutePath, PATHINFO_EXTENSION) === 'eml' ? 'message/rfc822' : null;
            }

            // Upload the file to AWS S3
            $s3Client->putObject([
                'Bucket' => 'esignaturepanda',
                'Key' => $kf,
                'Body' => $fileStream,
                'ACL' => 'public-read',
                'ContentType' => $contentType,
            ]);

            // Close the stream
            if ($isSignature === false) {
                $fileStream->close();
            }
        } catch (\Exception $e) {
            print_r($e);
            exit;
        }

        return $s3Client->getObjectUrl('esignaturepanda', $kf);
    }
}
