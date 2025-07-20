<?php

declare(strict_types=1);

use App\Exceptions\Renderers\FallbackExceptionRenderer;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

test('exception renderer returns a failed API response', function (): void {
    $request = Request::create('/api/test-fallback', 'GET', server: ['HTTP_ACCEPT' => 'application/json']);

    $exception = new RuntimeException('Something went wrong...');
    $renderer = new FallbackExceptionRenderer();

    expect($rendered = $renderer($exception, $request))->toBeInstanceOf(ApiResponse::class);

    /** @var JsonResponse */
    $response = $rendered->toResponse($request);

    expect($response->getStatusCode())
        ->toBe(Response::HTTP_INTERNAL_SERVER_ERROR)
        ->and($response->getData(true))->toMatchArray([
            'success' => false,
            'errors' => [
                'message' => 'Something went wrong...',
            ],
        ]);
});
