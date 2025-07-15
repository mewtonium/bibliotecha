<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class UserController extends Controller
{
    /**
     * Display the currently authenticated user from the token provided in the request.
     */
    public function me(Request $request)
    {
        Gate::authorize('view', $user = $request->user());

        return ApiResponse::success(new UserResource($user));
    }
}
