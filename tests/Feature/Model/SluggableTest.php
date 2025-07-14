<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Support\Models\SlugComposite;
use Tests\Support\Models\SlugMultiple;
use Tests\Support\Models\SlugSingle;

beforeEach(function (): void {
    Schema::create('test_sluggable', function (Blueprint $table): void {
        $table->id();
        $table->string('title')->nullable();
        $table->string('full_name')->nullable();
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('slug')->nullable();
        $table->string('url_slug')->nullable();
        $table->timestamps();
    });
});

afterEach(function (): void {
    Schema::dropIfExists('test_sluggable');
});

test('a slug can be generated from a single model attribute', function (): void {
    $model = SlugSingle::create(['title' => 'Test Example']);

    expect($model->slug)->toBe('test-example');
});

test('a slug can be generated from composite model attributes', function (): void {
    $model = SlugComposite::create([
        'first_name' => 'Test',
        'last_name' => 'Example',
    ]);

    expect($model->slug)->toBe('test-example');
});

test('a slug can be generated from multiple model attributes', function (): void {
    $model = SlugMultiple::create([
        'title' => 'Test Example',
        'full_name' => 'Another Test Example',
    ]);

    expect($model->slug)->toBe('test-example')
        ->and($model->url_slug)->toBe('another-test-example');
});

test('a suffixed slug can be generated if the original exists already', function (): void {
    SlugSingle::create(['title' => 'Test Example']);

    $model = SlugSingle::create(['title' => 'Test Example']);

    expect($model->slug)->toBe('test-example-1');
});

test('a slug can be generated using the next available suffix', function (): void {
    for ($i = 0; $i < 4; $i++) {
        SlugSingle::create(['title' => 'Test Example']);
    }

    SlugSingle::find(3)->delete();

    $model = SlugSingle::create(['title' => 'Test Example']);

    expect($model->slug)->toBe('test-example-2');
});

test('the original slug can be re-created if multiple suffixed slugs already exist', function (): void {
    for ($i = 0; $i < 3; $i++) {
        SlugSingle::create(['title' => 'Test Example']);
    }

    SlugSingle::find(1)->delete();

    $model = SlugSingle::create(['title' => 'Test Example']);

    expect($model->slug)->toBe('test-example');
});

test('a slug is properly stripped of unnecessary whitespace', function (): void {
    $model = SlugSingle::create(['title' => '   Test  Example   ']);

    expect($model->slug)->toBe('test-example');
});

test('a slug is not generated if the attribute is empty', function (): void {
    $model = SlugSingle::create(['title' => '']);

    expect($model->slug)->toBeNull();
});
