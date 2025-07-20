<?php

declare(strict_types=1);

namespace App\Exceptions\Renderers;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class ExceptionRenderer
{
    /**
     * Define the render logic for validation exceptions.
     *
     * @return \Closure(\Illuminate\Validation\ValidationException, \Illuminate\Http\Request): \App\Http\Responses\ApiResponse|null
     */
    public static function validation(): \Closure
    {
        return function (ValidationException $e, Request $request): ?ApiResponse {
            if (! $request->expectsJson()) {
                return null;
            }

            return (new ValidationExceptionRenderer())($e, $request);
        };
    }

    /**
     * Define the render logic for request throttling exceptions.
     *
     * @return \Closure(\Illuminate\Http\Exceptions\ThrottleRequestsException, \Illuminate\Http\Request): \App\Http\Responses\ApiResponse|null
     */
    public static function throttle(): \Closure
    {
        return function (ThrottleRequestsException $e, Request $request): ?ApiResponse {
            if (! $request->expectsJson()) {
                return null;
            }

            return (new ThrottleRequestsExceptionRenderer())($e, $request);
        };
    }

    /**
     * Define the render logic for any exception as a fallback.
     *
     * @return \Closure(\Throwable, \Illuminate\Http\Request): \App\Http\Responses\ApiResponse|null
     */
    public static function fallback(): \Closure
    {
        return function (\Throwable $e, Request $request): ?ApiResponse {
            if (! $request->expectsJson()) {
                return null;
            }

            return (new FallbackExceptionRenderer())($e, $request);
        };
    }
}
