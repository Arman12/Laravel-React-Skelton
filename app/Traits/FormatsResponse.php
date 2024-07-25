<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FormatsResponse
{


    /**
     * To format success response
     * @param  string  $message Message to format, $data is optional
     * @return string
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */
    protected function successResponse($message = null, $data = null): array
    {
        $message = $this->format($message);
        return is_string($message) ? ['success' => true, 'isValid' => true, 'message' => [$message], 'data' => $data] : ['success' => true, 'isValid' => true, 'message' => $message, 'data' => $data];
    }

    /**
     * To format error response
     * @param  string  $message Message to format
     * @return string
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */
    protected function errorResponse($message = null): array
    {
        $message = $this->format($message);
        return is_string($message) ? ['success' => true, 'isValid' => false, 'message' => [$message], 'data' => ''] : ['success' => true, 'isValid' => false, 'message' => $message, 'data' => ''];
    }

    /**
     * To format response
     * @param  string  $message Message to format
     * @return string
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */

    private function format($message)
    {
        if (is_string($message)) {
            return $this->formatMessage($message);
        } elseif (is_array($message)) {
            foreach ($message as $key => $error) {
                if (is_array($error)) {
                    $message[$key] = $this->format($error);
                } else {
                    $message[$key] = $this->formatMessage($error);
                }
            }
            return $message;
        } else {
            return $message;
        }
    }

    /**
     * To format response
     * @param  string  $message Message to format
     * @return string
     * Author: Arman Saleem
     * Date: 13 Sep, 2023
     */
    private function formatMessage(string $message): string
    {
        $message = str_replace('.', '', $message);
        return Str::snake(strtolower($message));
    }
}
