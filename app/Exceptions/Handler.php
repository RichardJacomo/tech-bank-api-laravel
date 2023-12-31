<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof AppError) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        if($exception instanceof ValidationException){
            return response()->json([
                'message' => $exception->validator->errors()
            ], 422);
        }

        if($exception instanceof AuthorizationException){
            return response()->json([
                'message' => $exception->getMessage()
            ], 403);
        }

        if($exception instanceof NotFoundHttpException){
            return response()->json([
                'message' => $exception->getMessage()
            ], 404);
        };

        return response()->json([
            'message' => 'Something went wrong',
        ], 500);
    }
}
