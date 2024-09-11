<?php

namespace App\Queries\Services;

use App\Queries\Models\User;
use App\Shared\JwtGenerator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class UserQueryService
{
    public function __construct(private readonly JwtGenerator $tokenGenerator) {}

    public function getByUserId(string $userId): ?User
    {
        $dto = DB::table('users')->where('id', $userId)->first();

        if (is_null($dto)) {
            return null;
        }

        return new User(
            $dto->id,
            $dto->username,
            $dto->email,
            $this->tokenGenerator->generateToken($dto->id),
            $dto->bio,
            $dto->image,
            CarbonImmutable::parse($dto->created_at),
            CarbonImmutable::parse($dto->updated_at),
        );
    }
}
