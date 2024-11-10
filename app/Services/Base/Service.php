<?php

namespace App\Services\Base;

use App\Logging\AppLogger;

abstract class Service
{
    protected AppLogger $appLogger;

    /**
     * @param string $message
     * @param array $data
     * @return array
     */
    protected function successReturn(string $message, array $data = []): array
    {
        $this->appLogger->steps($message, $data);

        return [
            'error' => false,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * @param string $message
     * @param string $errorCode
     * @return array
     */
    protected function errorReturn(string $message, string $errorCode): array
    {
        $this->appLogger->steps($message, [
            'payload' => $errorCode ?? null
        ]);

        return [
            'error' => true,
            'message' => $message,
            'errorCode' => $errorCode
        ];
    }
}
