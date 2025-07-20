<?php

declare(strict_types=1);

use App\Exceptions\Renderers\ValidationExceptionRenderer;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

test('exception renderer returns a failed API response', function (): void {
    $request = Request::create('/api/test-validation', 'POST', server: ['HTTP_ACCEPT' => 'application/json']);

    $validator = Validator::make(data: [], rules: ['name' => 'required']);

    $exception = new ValidationException($validator);
    $renderer = new ValidationExceptionRenderer();

    expect($rendered = $renderer($exception, $request))->toBeInstanceOf(ApiResponse::class);

    /** @var JsonResponse */
    $response = $rendered->toResponse($request);

    expect($response->getStatusCode())
        ->toBe(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->and($response->getData(true))->toMatchArray([
            'success' => false,
            'errors' => [
                'message' => 'Validation failed on one or more fields',
                'fields' => [
                    'name' => ['The name field is required.'],
                ],
            ],
        ]);
});
