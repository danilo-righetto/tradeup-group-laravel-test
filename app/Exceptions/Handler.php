<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Traits\ResponseUtils;
use App\Enums\RequestStatusCodeEnum;
use App\Logging\AppLogger;

class Handler extends ExceptionHandler
{
    use ResponseUtils;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $logger = new AppLogger;
        $logger->logError($e->getMessage(), [
            'erro' => $e->getTraceAsString()
        ])->showLogs();

        if ($e instanceof ValidationException) {
            return $this->responseError(
                RequestStatusCodeEnum::BAD_REQUEST->value,
                $e->validator->errors()->all()
            );
        } else {
            return $this->responseError(
                RequestStatusCodeEnum::INTERNAL_SERVER_ERROR->value,
                $e->getMessage()
            );
        }

        return parent::render($request, $e);
    }
}
