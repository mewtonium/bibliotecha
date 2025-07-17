<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Filters\Concerns\PipesThroughFilters;

abstract class Controller
{
    use PipesThroughFilters;
}
