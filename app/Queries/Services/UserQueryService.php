<?php declare(strict_types=1);

namespace App\Queries\Services;

use App\Queries\Models\User;
use App\Shared\Jwt\JwtEncoder;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class UserQueryService
{
    public function __construct(private readonly JwtEncoder $jwtManager) {}

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
            $this->jwtManager->encode($dto->id),
            $dto->bio,
            $dto->image,
            CarbonImmutable::parse($dto->created_at),
            CarbonImmutable::parse($dto->updated_at),
        );
    }

    public function getUserIdFromUsername(string $username): ?string
    {
        $dto = DB::table('users')->where('username', $username)->first(['id']);
        return $dto?->id;
    }
}
