<?php

namespace App\Shared\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtManager
{
    public function __construct(
        private readonly string $appKey,
    ) {}

    public function encode(string $userId): string
    {
        $payload = [
            'user_id' => $userId,
        ];

        return JWT::encode($payload, $this->appKey, 'HS256');
    }

    public function decode(string $jwt): string
    {
        $decoded = (array) JWT::decode($jwt, new Key($this->appKey, 'HS256'));

        if (!in_array('user_id', $decoded, strict: true)) {
            throw new InvalidJwtException("invalid jwt\n{$jwt}");
        }

        return $decoded['user_id'];
    }
}
