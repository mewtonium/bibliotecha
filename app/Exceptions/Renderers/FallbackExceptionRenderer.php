<?php

declare(strict_types=1);

namespace App\Exceptions\Renderers;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class FallbackExceptionRenderer
{
    public function __invoke(\Throwable $e, Request $request): ApiResponse
    {
        $response = (new ExceptionHandler(app()))->render($request, $e);

        return ApiResponse::failed(
            errors: json_decode($response->getContent(), true) ?: ['message' => 'An unknown error occurred...'],
            status: $response->getStatusCode() ?: Response::HTTP_BAD_REQUEST,
            exception: $e,
        );
    }
}
