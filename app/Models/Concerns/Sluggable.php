<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Sluggable
{
    /**
     * The sluggable boot method.
     */
    public static function bootSluggable(): void
    {
        static::creating(function (Model $model): void {
            foreach ($model->sluggable() as $sluggable => $from) {
                $model->{$sluggable} = $model->makeSlug($sluggable, $from);
            }
        });
    }

    /**
     * The attributes that are sluggable.
     *
     * @var list<string, string|list<string>>
     */
    public function sluggable(): array
    {
        return [];
    }

    /**
     * Makes a unique slug for the given model sluggable attribute.
     */
    protected function makeSlug(string $sluggable, array|string $from): string|null
    {
        // Generate the base slug from either a single or composite model attribute.
        $slug = is_array($from)
            ? Str::slug(implode('-', $this->only($from)))
            : Str::slug($this->{$from});

        if (empty($slug)) {
            return null;
        }

        // Get all existing slugs that match exactly or have a numeric suffix as
        // we'll need this later to determine the next available suffix to use.
        $existingSlugs = $this->newQuery()
            ->select($sluggable)
            ->where('slug', $slug)
            ->orWhere('slug', 'regexp', '^' . preg_quote($slug) . '-\d+$')
            ->get()
            ->pluck($sluggable);

        // If the base slug is already taken, find the next available suffix by checking for gaps
        // in the sequence. Otherwise, just skip this logic as the base slug will be used.
        if ($existingSlugs->contains($slug)) {
            // Extract a list of suffix numbers from the list of existing slugs.
            $nums = $existingSlugs
                ->filter(fn ($item) => $item !== $slug)
                ->map(fn ($item) => (int) str_replace($slug . '-', '', $item))
                ->sort()
                ->values()
                ->toArray();

            // Find the first available gap in the sequence, if there is one.
            if (count($nums) > 0) {
                foreach (range(1, max($nums)) as $num) {
                    if (! in_array($num, $nums)) {
                        $suffix = $num;
                        break;
                    }
                }
            }

            // If a gap was found, use the suffix. Otherwise, increment the
            // max suffix or start at 1 if only the base slug exists.
            $suffix ??= max($nums ?: [0]) + 1;

            $slug = "{$slug}-{$suffix}";
        }

        return $slug;
    }
}
