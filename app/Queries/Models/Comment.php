<?php declare(strict_types=1);

namespace App\Queries\Models;

use Carbon\CarbonImmutable;

final class Comment
{
    public function __construct(
        public readonly string $id,
        public readonly string $body,
        public readonly CarbonImmutable $createdAt,
        public readonly CarbonImmutable $updatedAt,
        public readonly Profile $author,
    ) {}
}
