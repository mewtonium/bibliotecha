<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

describe('GET /api/v1/users/me', function (): void {
    test('the endpoint returns the correct response', function (): void {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->getJson('/api/v1/users/me');

        expect($response->getStatusCode())
            ->toBe(Response::HTTP_OK)
            ->and($response->json())
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

    test('the endpoint can load in reviews', function (): void {
        $user = User::factory()
            ->hasReviews(5)
            ->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/users/me?with=reviews');

        expect($response->getStatusCode())
            ->toBe(Response::HTTP_OK)
            ->and($data = $response->json('data'))
            ->toHaveKey('reviews')
            ->and($data['reviews'])
            ->toHaveCount(5);
    });

    test('the endpoint will not load in invalid relations or those that are valid but were not requested', function (): void {
        $user = User::factory()
            ->hasReviews(5)
            ->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/users/me?with=testing');

        expect($response->getStatusCode())
            ->toBe(Response::HTTP_OK)
            ->and($response->json('data'))
            ->not->toHaveKey('reviews')
            ->not->toHaveKey('testing');
    });
});
