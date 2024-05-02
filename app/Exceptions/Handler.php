<?php

namespace App\Exceptions;

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

            return apiResponse()
                ->errors($errors)
                ->message($exception->getMessage())
                ->send(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return apiResponse()
                ->message('Not Found')
                ->send(Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof UnauthorizedException) {
            return apiResponse()
                ->message($exception->getMessage())
                ->send(Response::HTTP_FORBIDDEN);
        }


        if ($exception instanceof BadRequestException) {
            return apiResponse()
                ->message($exception->getMessage())
                ->send(Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = Response::$statusTexts[$statusCode];

            return apiResponse()
                ->message($message)
                ->send($statusCode);
        }

        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        return apiResponse()
            ->message('Unexpected Error , try later please')
            ->send(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
