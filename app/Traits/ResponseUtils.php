<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseUtils
{
    public static function responseError(int $statusCode = 400, $mensagem = null, $codigoErro = null, $errors = null): JsonResponse
    {
        $errorPayload = [];
        if ($mensagem != null) $errorPayload['mensagem'] = $mensagem;
        if ($codigoErro != null) $errorPayload['codigoErro'] = $codigoErro;
        if ($errors != null) $errorPayload['errors'] = $errors;

        $payload = [
            'version' => getenv('VERSION'),
            'status' => $statusCode,
            'error' => $errorPayload
        ];

        return response()
            ->json($payload, $statusCode, [], JSON_UNESCAPED_UNICODE)
            ->header('Content-Type', 'application/json');
    }

    public static function responseSuccess(int $statusCode = 200, $body = null): JsonResponse
    {
        $payload = $statusCode == 204 ? [] : [
            'version' => getenv('VERSION'),
            'status' => $statusCode,
        ];
        if ($body != null) $payload['body'] = $body;

        return response()
            ->json($payload, $statusCode, [], JSON_UNESCAPED_UNICODE)
            ->header('Content-Type', 'application/json');
    }
}
