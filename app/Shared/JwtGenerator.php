<?php

namespace App\Shared;

use Firebase\JWT\JWT;

final class JwtGenerator
{
    public function __construct(
        private readonly string $appKey,
    ) {}

    public function generateToken(string $userId): string
    {
        $payload = [
            'user_id' => $userId,
        ];

        return JWT::encode($payload, $this->appKey, 'HS256');
    }
}
