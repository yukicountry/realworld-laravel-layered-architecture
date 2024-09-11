<?php

namespace App\Commands\Services\User;

use App\Commands\Models\User\InvalidCredentialException;
use App\Commands\Models\User\User;
use App\Commands\Models\User\UserRepository;

final class AuthenticationService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    public function handle(string $email, string $rawPassword): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (is_null($user)) {
            throw new InvalidCredentialException("user does not exist (email: {$email})");
        }

        $user->verifyPassword($rawPassword);

        return $user;
    }
}
