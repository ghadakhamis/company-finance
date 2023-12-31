<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
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
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(
            function (AuthenticationException $exception, $request) {
                return response()->json(
                    ['message' => trans('auth.unauthorized'), 'errors' => null], Response::HTTP_UNAUTHORIZED
                );  
            }
        );
        $this->renderable(
            function (HttpException $exception, $request) {
                return response()->json(
                    ['message' => $exception->getMessage(), 'errors' => null], $exception->getStatusCode()
                );  
            }
        );
    }
}
