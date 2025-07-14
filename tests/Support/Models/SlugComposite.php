<?php

declare(strict_types=1);

namespace Tests\Support\Models;

use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Model;

final class SlugComposite extends Model
{
    use Sluggable;

    protected $table = 'test_sluggable';

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => ['first_name', 'last_name'],
        ];
    }
}
