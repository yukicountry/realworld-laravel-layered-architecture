<?php

declare(strict_types=1);

namespace App\Shared\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtEncoder
{
    public function __construct(
        private readonly string $appKey,
        private readonly int $secondsUntilExpires = 3600,
    ) {}

    public function encode(string $userId): string
    {
        $payload = [
            'exp'     => time() + $this->secondsUntilExpires,
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
