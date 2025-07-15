<?php

declare(strict_types=1);

use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;

test('a successful response is returned using an API resource', function (): void {
    $user = User::factory()->create();
    $resource = new UserResource($user);

    /** @var JsonResponse */
    $response = ApiResponse::success($resource)->toResponse(request());

    expect($response->getStatusCode())->toBe(200);

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

    expect($response->getStatusCode())->toBe(200);

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

test('a failed response is returned', function (): void {
    /** @var JsonResponse */
    $response = ApiResponse::failed(['error' => 'Something went wrong...'])->toResponse(request());

    expect($response->getStatusCode())->toBe(400);

    expect($response->getData(assoc: true))
        ->toMatchArray([
            'success' => false,
            'error' => 'Something went wrong...',
        ]);
});
