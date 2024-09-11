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

        if (!array_key_exists('user_id', $decoded)) {
            throw new InvalidJwtException("invalid jwt {$jwt}");
        }

        return $decoded['user_id'];
    }
}
