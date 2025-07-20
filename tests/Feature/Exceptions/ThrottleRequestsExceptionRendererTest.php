<?php

declare(strict_types=1);

use App\Exceptions\Renderers\ThrottleRequestsExceptionRenderer;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

test('exception renderer returns a failed API response', function (): void {
    $request = Request::create('/api/test-throttle', 'POST', server: ['HTTP_ACCEPT' => 'application/json']);

    $exception = new ThrottleRequestsException('Too Many Attempts.');
    $renderer = new ThrottleRequestsExceptionRenderer();

    expect($rendered = $renderer($exception, $request))->toBeInstanceOf(ApiResponse::class);

    /** @var JsonResponse */
    $response = $rendered->toResponse($request);

    expect($response->getStatusCode())
        ->toBe(Response::HTTP_TOO_MANY_REQUESTS)
        ->and($response->getData(true))->toMatchArray([
            'success' => false,
            'errors' => [
                'message' => 'Too Many Attempts.',
            ],
        ]);
});
