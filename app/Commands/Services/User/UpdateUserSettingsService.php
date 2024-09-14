<?php declare(strict_types=1);

namespace App\Commands\Services\User;

use App\Commands\Models\User\User;
use App\Commands\Models\User\UserNotFoundException;
use App\Commands\Models\User\UserRepository;

final class UpdateUserSettingsService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    public function handle(string $userId, array $newAttributes): User
    {
        $user = $this->userRepository->findById($userId);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        $user->update($newAttributes);

        $this->userRepository->saveUser($user);

        return $user;
    }
}
