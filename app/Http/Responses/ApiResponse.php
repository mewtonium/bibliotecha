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
        protected JsonResource|array $data,
        protected int $status,
        protected bool $success = true,
    ) {
        // ...
    }

    /**
     * Return a successful API response.
     */
    public static function success(JsonResource|array $data, int $status = Response::HTTP_OK): static
    {
        return new static($data, $status, success: true);
    }

    /**
     * Return a failed API response.
     *
     * If an exception is provided and `app.debug` is enabled in config,
     * then extra debugging data will also be returned.
     */
    public static function failed(array $errors, int $status = Response::HTTP_BAD_REQUEST, ?\Throwable $exception = null): static
    {
        $data = [
            'errors' => $errors,
        ];

        if (! is_null($exception) && config('app.debug')) {
            $data['debug'] = [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'trace' => collect($exception->getTrace())->take(10)->all(),
            ];
        }

        return new static($data, $status, success: false);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $data = $this->data instanceof JsonResource
            ? $this->data->response($request)->getData(assoc: true)
            : (array) $this->data;

        return response()->json(
            status: $this->status,
            data: [
                'success' => $this->success,
                ...$data,
            ],
        );
    }
}
