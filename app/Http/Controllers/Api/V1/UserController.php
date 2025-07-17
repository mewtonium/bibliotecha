<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\CurrentUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\Concerns\InteractsWithRelations;

final class UserController extends Controller
{
    use InteractsWithRelations;

    /**
     * Display the currently authenticated user from the token provided in the request.
     */
    public function me(CurrentUserRequest $request): ApiResponse
    {
        $user = $request->user();

        $this->loadRelationsFromRequest($user, $request);

        return ApiResponse::success(new UserResource($user));
    }
}
