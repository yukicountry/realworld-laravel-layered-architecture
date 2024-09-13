<?php

declare(strict_types=1);

namespace App\Commands\Services\User;

use App\Commands\Models\User\CheckUserExistsByEmail;
use App\Commands\Models\User\CheckUserExistsByUsername;
use App\Commands\Models\User\User;
use App\Commands\Models\User\UserRepository;

final class RegisterUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CheckUserExistsByEmail $checkUserExistsByEmail,
        private readonly CheckUserExistsByUsername $checkUserExistsByUsername,
    ) {}

    public function handle(array $input): User
    {
        $user = User::createNewUser(
            $this->checkUserExistsByEmail,
            $this->checkUserExistsByUsername,
            $input['username'],
            $input['email'],
            $input['password']
        );

        $this->userRepository->saveUser($user);

        return $user;
    }
}
