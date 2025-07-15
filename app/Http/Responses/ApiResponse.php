<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

final class ApiResponse implements Responsable
{
    /**
     * Create a new instance of an API response.
     */
    public function __construct(
        protected JsonResource|array $resource,
        protected int $status,
        protected bool $success = true,
    ) {
        // ...
    }

    /**
     * Return a successful API response.
     */
    public static function success(JsonResource|array $resource, int $status = Response::HTTP_OK): static
    {
        return new static($resource, $status, success: true);
    }

    /**
     * Return a failed API response.
     */
    public static function failed(array $resource, int $status = Response::HTTP_BAD_REQUEST): static
    {
        return new static($resource, $status, success: false);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $resource = $this->resource instanceof JsonResource
            ? $this->resource->response($request)->getData(assoc: true)
            : (array) $this->resource;

        return response()->json(
            status: $this->status,
            data: [
                'success' => $this->success,
                ...$resource,
            ],
        );
    }
}
