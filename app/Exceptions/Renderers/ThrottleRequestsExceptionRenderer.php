<?php

declare(strict_types=1);

namespace App\Exceptions\Renderers;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ThrottleRequestsExceptionRenderer
{
    public function __invoke(ThrottleRequestsException $e, Request $request): ApiResponse
    {
        $response = (new ExceptionHandler(app()))->render($request, $e);

        return ApiResponse::failed(
            errors: ['message' => $e->getMessage()],
            status: Response::HTTP_TOO_MANY_REQUESTS,
            exception: $e,
            headers: $response->headers->all(),
        );
    }
}
