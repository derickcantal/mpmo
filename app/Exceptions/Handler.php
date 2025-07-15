<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use \Illuminate\Foundation\Exceptions\HandlesExceptions;

    /**
     * A list of exception types that should _not_ be reported/logged.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpResponseException::class,
        ValidationException::class,
        // add other exception classes here if you don't want them in your logs
    ];

    /**
     * A list of input fields that should never be flashed to the session on validation errors.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register any exception handling callbacks for the application.
     * 
     * Use this to customize reporting or to add custom “renderable” callbacks.
     */
    public function register(): void
    {
        // Example: report a custom external service
        $this->reportable(function (Throwable $e) {
            // if ($e instanceof SomeSpecificException) {
            //     ExternalErrorTracker::capture($e);
            // }
        });

        // Example: turn a NotFoundHttpException into a JSON response
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });
    }

    /**
     * Convert an exception into an HTTP response that will be sent back to the browser.
     *
     * You can override this to handle things like 403, 404 or custom exception types.
     */
    public function render($request, Throwable $exception)
    {
        // Example: custom 403 page
        if ($exception instanceof AuthorizationException) {
            return response()->view('errors.403', [], 403);
        }

        // Example: custom 404 page
        if ($this->isHttpException($exception) && $exception->getStatusCode() === 404) {
            return response()->view('errors.404', [], 404);
        }

        // For all other exceptions, fall back to the default behavior:
        return parent::render($request, $exception);
    }
}
