<?php

declare(strict_types=1);

namespace App\Commands\Models\User;

interface UserRepository
{
    /**
     * Create or update the user.
     * @param User $user user to save
     */
    function saveUser(User $user): void;

    /**
     * Find user by id.
     * If does not exist, return null.
     * @param string $userId user id
     * @return User|null
     */
    function findById(string $userId): ?User;

    /**
     * Find user by username.
     * If does not exist, return null.
     * @param string $username username
     * @return User|null
     */
    function findByUsername(string $username): ?User;

    /**
     * Find user by email.
     * If does not exist, return null.
     * @param string $email email
     * @return User|null
     */
    function findByEmail(string $email): ?User;
}
