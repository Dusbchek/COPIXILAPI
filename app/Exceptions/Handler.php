<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $exception->errors()
            ], 422);
        }

        if ($exception instanceof HttpExceptionInterface) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'HTTP error occurred.'
            ], $exception->getStatusCode());
        }

        return response()->json([
            'message' => 'Unexpected error occurred.',
            'error' => config('app.debug') ? $exception->getMessage() : null,
        ], 500);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
