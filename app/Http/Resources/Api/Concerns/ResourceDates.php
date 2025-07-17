<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\Concerns;

use Carbon\CarbonInterface;

trait ResourceDates
{
    /**
     * Return the resource `created_at` and `updated_at` timestamp attributes formatted.
     * The soft delete column `deleted_at` is conditionally formatted.
     *
     * @return array{updated_at: string, created_at: string, deleted_at?: string|null}
     */
    public function timestamps(): array
    {
        return [
            'updated_at' => $this->date($this->updated_at),
            'created_at' => $this->date($this->created_at),
            'deleted_at' => $this->when(
                ! is_null($this->deleted_at),
                fn () => $this->date($this->deleted_at),
            ),
        ];
    }

    /**
     * Returns a formatted date resource attribute.
     */
    public function date(?CarbonInterface $date): ?string
    {
        return $date?->toDateTimeString();
    }
}
