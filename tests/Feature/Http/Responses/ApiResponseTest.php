<?php

declare(strict_types=1);

use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

test('a successful response is returned using an API resource', function (): void {
    $user = User::factory()->create();
    $resource = new UserResource($user);

    /** @var JsonResponse */
    $response = ApiResponse::success($resource)->toResponse(request());

    expect($response->getStatusCode())->toBe(Response::HTTP_OK);

    expect($response->getData(assoc: true))
        ->toMatchArray([
            'success' => true,
            'data' => [
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => (string) $user->created_at,
                'updated_at' => (string) $user->updated_at,
            ],
        ]);
});

test('a successful response is returned using an array of data', function (): void {
    $user = User::factory()->create();

    /** @var JsonResponse */
    $response = ApiResponse::success(['data' => $user->toArray()])->toResponse(request());

    expect($response->getStatusCode())->toBe(Response::HTTP_OK);

    expect($response->getData(assoc: true))
        ->toMatchArray([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at->toJSON(),
                'created_at' => $user->created_at->toJSON(),
                'updated_at' => $user->updated_at->toJSON(),
            ],
        ]);
});

test('a failed response is returned with errors', function (): void {
    $errors = [
        ['first_name' => 'The first name field is required'],
        ['last_name' => 'The last name field is required'],
    ];

    /** @var JsonResponse */
    $response = ApiResponse::failed($errors)->toResponse(request());

    expect($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST);

    expect($response->getData(assoc: true))
        ->toMatchArray([
            'success' => false,
            'errors' => $errors,
        ]);
});

test('a failed response is returned with extra debug info if app.debug is enabled and exception provided', function (): void {
    config(['app.debug' => true]);

    $errors = [
        ['first_name' => 'The first name field is required'],
        ['last_name' => 'The last name field is required'],
    ];

    /** @var JsonResponse */
    $response = ApiResponse::failed($errors, exception: new \Exception('Form validation failed'))->toResponse(request());

    expect($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST);

    expect($data = $response->getData(assoc: true))
        ->toMatchArray([
            'success' => false,
            'errors' => $errors,
        ])
        ->and($data)->toHaveKey('debug')
        ->and($data['debug'])->toHaveKeys(['exception', 'message', 'file', 'line', 'code', 'trace']);
});

test('a failed response is not returned with extra debug info if app.debug is disabled but exception still provided', function (): void {
    config(['app.debug' => false]);

    $errors = [
        ['first_name' => 'The first name field is required'],
        ['last_name' => 'The last name field is required'],
    ];

    /** @var JsonResponse */
    $response = ApiResponse::failed($errors, exception: new \Exception('Form validation failed'))->toResponse(request());

    expect($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST);

    expect($data = $response->getData(assoc: true))
        ->toMatchArray([
            'success' => false,
            'errors' => $errors,
        ])
        ->and($data)->not->toHaveKey('debug');
});

test('a failed response which would normally return debug as part of its response data info is stripped out and is in the extra debug info instead', function (): void {
    config(['app.debug' => true]);

    /** @var JsonResponse */
    $response = ApiResponse::failed(['message' => 'Something went wrong...'], exception: new \RuntimeException('Something went wrong...'))->toResponse(request());

    expect($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST);

    expect($data = $response->getData(assoc: true))
        ->toMatchArray([
            'success' => false,
            'errors' => [
                'message' => 'Something went wrong...',
            ],
        ])
        ->and($data['errors'])->not->toHaveKeys(['exception', 'file', 'line', 'trace'])
        ->and($data)->toHaveKey('debug')
        ->and($data['debug'])->toHaveKeys(['exception', 'message', 'file', 'line', 'code', 'trace']);
});

test('a response can include custom headers', function (): void {
    $headers = [
        'retry-after' => 60,
        'x-ratelimit-limit' => 2,
        'x-ratelimit-remaining' => 0,
        'x-ratelimit-reset' => 1721479999,
    ];

    /** @var JsonResponse */
    $response = ApiResponse::failed(
        errors: ['message' => 'Something went wrong...'],
        status: Response::HTTP_TOO_MANY_REQUESTS,
        exception: new ThrottleRequestsException('Too May Attempts.'),
        headers: $headers,
    )->toResponse(request());

    foreach ($headers as $key => $value) {
        expect($response->headers->get($key))->toBe((string) $value);
    }
});
