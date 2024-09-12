<?php

namespace App\Queries\Models;

final class Profile
{
    public function __construct(
        public readonly string $username,
        public readonly ?string $bio,
        public readonly ?string $image,
        public readonly bool $following,
    ) {}
}
