<?php

namespace App\Services;

use App\Exceptions\Application\ApplicationException;
use Illuminate\Support\Facades\Storage;
use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;

class VerifySignService
{
    /**
     * To verify the signature source for validation
     * @param  string  $signature
     * @throws \App\Exceptions\ApplicationException
     * @return string
     */
    public function verify(string $signature): string
    {
        $response  = array("valid" => true, "message" => "");
        if ($signature) {
            $id = preg_replace("/[^0-9.]/", "", microtime() . rand(10, 1000000));
            list($type, $signature) = explode(';', $signature);
            list(, $signature) = explode(',', $signature);
            $signature = base64_decode($signature);

            $imageName = $id . 'main-sign.png';
            $imageNamePath = 'tempimages/' . $imageName;
            Storage::disk('public')->put($imageNamePath, $signature);
            $fullPath = Storage::path('public/'.$imageNamePath);

            $size = getimagesize($fullPath);
            $pixels = ($size[0] * $size[1]);
            try {
                $palette = Palette::fromFilename($fullPath);
                $colorCount = count($palette);
                if ($colorCount > 0) {
                    $blackCount = $palette->getColorCount(Color::fromHexToInt('#000000'));
                    if ($blackCount == $pixels) {
                        $response  = array("valid" => false, "message" => "Sign is black");
                    }
                } else {
                    $response  = array("valid" => false, "message" => "Sign is transparent");
                }
            } catch (\Exception $e) {
                $response  = array("valid" => false, "message" => "error in api");
                // return $response;
            }
            Storage::disk('public')->delete($imageNamePath);
            return json_encode($response);
        } else {
            throw new ApplicationException('empty sign');
            $response  = array("valid" => false, "message" => "empty sign");
            return json_encode($response);
        }
    }
}
