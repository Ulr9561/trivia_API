<?php

namespace App\Exceptions;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class HandlerException extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ModelNotFoundException $e, $message) {
            return response()->json(['error' => 'Resource not found'], 404);
        });

        $this->renderable(function (UnauthorizedHttpException $e, $request) {
            return response()->json(['error' => 'Unauthorized'], 401);
        });

        $this->renderable(function (ValidationException $e, $request) {
            return response()->json(['errors' => $e->errors()], 422);
        });

        $this->renderable(function (NotFoundHttpException $e, $message) {
            return response()->json(['error' => $message], 404);
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            return response()->json(['error' => 'This action is unauthorized.'], 403);
        });

        $this->renderable(function (AccessDeniedException $e, $request) {
            return response()->json(['error' => 'Access denied'], 403);
        });

        $this->renderable(function (Throwable $e, $request) {
            return response()->json(['error' => 'Server Error'], 500);
        });
    }

}
