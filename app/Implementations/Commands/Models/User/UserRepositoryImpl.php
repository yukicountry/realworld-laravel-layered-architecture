<?php declare(strict_types=1);

namespace App\Implementations\Commands\Models\User;

use App\Commands\Models\User\User;
use App\Commands\Models\User\UserRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class UserRepositoryImpl implements UserRepository
{
    public function saveUser(User $user): void
    {
        $dto = $this->mapToDto($user);

        DB::transaction(function () use ($dto) {
            DB::table('users')->upsert($dto, 'id');
        });
    }

    public function findById(string $userId): ?User
    {
        $dto = DB::table('users')->where('id', $userId)->first();

        if (is_null($dto)) {
            return null;
        }

        return $this->mapToModel($dto);
    }

    public function findByEmail(string $email): ?User
    {
        $dto = DB::table('users')->where('email', $email)->first();

        if (is_null($dto)) {
            return null;
        }

        return $this->mapToModel($dto);
    }

    public function findByUsername(string $username): ?User
    {
        $dto = DB::table('users')->where('username', $username)->first();

        if (is_null($dto)) {
            return null;
        }

        return $this->mapToModel($dto);
    }

    private function mapToDto(User $model): array
    {
        return [
            'id'         => $model->id,
            'username'   => $model->username,
            'email'      => $model->email,
            'password'   => $model->password,
            'bio'        => $model->bio,
            'image'      => $model->image,
            'created_at' => $model->createdAt,
            'updated_at' => $model->updatedAt,
        ];
    }

    private function mapToModel(object $dto): User
    {
        return User::reconstruct(
            $dto->id,
            $dto->username,
            $dto->email,
            $dto->password,
            $dto->bio,
            $dto->image,
            CarbonImmutable::parse($dto->created_at),
            CarbonImmutable::parse($dto->updated_at),
        );
    }
}
