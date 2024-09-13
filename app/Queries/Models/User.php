<?php

declare(strict_types=1);

namespace App\Queries\Models;

use Carbon\CarbonImmutable;

final class User
{
    public function __construct(
        public readonly string $id,
        public readonly string $username,
        public readonly string $email,
        public readonly string $token,
        public readonly ?string $bio,
        public readonly ?string $image,
        public readonly CarbonImmutable $createdAt,
        public readonly CarbonImmutable $updatedAt,
    ) {}
}
