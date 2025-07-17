<?php

declare(strict_types=1);

use App\Models\Concerns\InteractsWithRelations;
use App\Models\User;
use Illuminate\Http\Request;

beforeEach(function (): void {
    $this->class = new class () {
        use InteractsWithRelations;
    };
});

dataset('targets', [
    'model' => fn () => User::factory()->create(),
    'collection' => fn () => User::factory(10)->create(),
    'builder' => fn () => User::query(),
]);

describe('relationExists()', function (): void {
    test('only a valid relation is found', function (\Closure $target): void {
        expect($this->class->relationExists($target(), 'reviews'))->toBeTrue();
        expect($this->class->relationExists($target(), 'testing'))->toBeFalse();
    });
})->with('targets');

describe('getExistingRelations()', function (): void {
    test('only a list of valid relations are returned', function (\Closure $target): void {
        expect($this->class->getExistingRelations($target(), 'reviews,testing,example'))->toBe(['reviews']);
        expect($this->class->getExistingRelations($target(), ['reviews', 'testing', 'example']))->toBe(['reviews']);
    })->with('targets');

    test('that malformed relations are corrected and filtered, if necessary', function (): void {
        $user = User::factory()->create();

        expect($this->class->getExistingRelations($user, '  Rev iew s,testing, ,exa mple'))->toBe(['reviews']);
    });
});

describe('loadRelationsFromRequest()', function (): void {
    test('only valid relations are loaded onto a single model from a request', function (): void {
        $user = User::factory()->create();
        $request = new Request(query: ['with' => 'reviews,testing,example']);

        $this->class->loadRelationsFromRequest($user, $request);

        expect($user->relationLoaded('reviews'))->toBeTrue()
            ->and($user->relationLoaded('testing'))->toBeFalse()
            ->and($user->relationLoaded('example'))->toBeFalse();
    });

    test('only valid relations are loaded onto a model collection from a request', function (): void {
        $users = User::factory(10)->create();
        $request = new Request(query: ['with' => 'reviews,testing,example']);

        $this->class->loadRelationsFromRequest($users, $request);

        expect($users->first()->relationLoaded('reviews'))->toBeTrue()
            ->and($users->first()->relationLoaded('testing'))->toBeFalse()
            ->and($users->first()->relationLoaded('example'))->toBeFalse();
    });
});
