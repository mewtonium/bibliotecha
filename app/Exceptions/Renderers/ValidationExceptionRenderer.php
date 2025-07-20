<?php

declare(strict_types=1);

namespace App\Exceptions\Renderers;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

final class ValidationExceptionRenderer
{
    public function __invoke(ValidationException $e, Request $request): ApiResponse
    {
        return ApiResponse::failed(
            errors: ['message' => 'Validation failed on one or more fields', 'fields' => $e->errors()],
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            exception: $e,
        );
    }
}
