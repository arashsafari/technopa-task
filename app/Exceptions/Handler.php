<?php

namespace App\Exceptions;

use App\Models\User;
use App\Notifications\SearchOrderExceptionNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
        // $this->reportable(function (Throwable $e) {
        //     //
        // });
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->messages();
            return $this->renderError($exception, Response::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage(), $errors);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->renderError($exception, Response::HTTP_NOT_FOUND, 'Not Found');
        }

        if ($exception instanceof UnauthorizedException) {
            return $this->renderError($exception, Response::HTTP_FORBIDDEN, $exception->getMessage());
        }

        if ($exception instanceof BadRequestException) {
            return $this->renderError($exception, Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = Response::$statusTexts[$statusCode];
            return $this->renderError($exception, $statusCode, $message);
        }

        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        return $this->renderError($exception, Response::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected Error , try later please');
    }

    private function renderError(Throwable $e, $status, ?string $message = null, $errors = [], $data = [])
    {

        // $ExceptionNotification = new SearchOrderExceptionNotification(
        //     $message ? $message : $e->getMessage(),
        //     json_encode($e->getTrace(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        // );

        // User::whereEmail(config('settings.technopay.admin_email'))->first()
        //     ->notify($ExceptionNotification);

        return apiResponse()
            ->message($message ? $message : $e->getMessage())
            ->data($data)
            ->errors($errors)
            ->send($status);
    }
}
