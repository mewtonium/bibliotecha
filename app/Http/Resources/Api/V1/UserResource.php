<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\Concerns\ResourceDates;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    use ResourceDates;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            ...$this->timestamps(),
            'reviews' => $this->whenLoaded('reviews'),
        ];
    }
}
