<?php

namespace App\Commands\Models\User;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

final class User
{
    private function __construct(
        public readonly string $id,
        public string $username,
        public string $email,
        public string $password,
        public ?string $bio,
        public ?string $image,
        public CarbonImmutable $createdAt,
        public CarbonImmutable $updatedAt,
    ) {}

    public static function createNewUser(
        CheckUserExistsByEmail $checkUserExistsByEmail,
        CheckUserExistsByUsername $checkUserExistsByUsername,
        string $username,
        string $email,
        string $password,
    ): self {
        if ($checkUserExistsByEmail->handle($email)) {
            throw new EmailDuplicatedException("user of email already exists ({$email})");
        }

        if ($checkUserExistsByUsername->handle($username)) {
            throw new UsernameDuplicatedException("username already exists ({$username})");
        }

        return new self(
            Uuid::uuid7()->toString(),
            $username,
            $email,
            Hash::make($password),
            null,
            null,
            CarbonImmutable::now(),
            CarbonImmutable::now(),
        );
    }

    public static function reconstruct(
        string $id,
        string $username,
        string $email,
        string $password,
        ?string $bio,
        ?string $image,
        CarbonImmutable $createdAt,
        CarbonImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $username,
            $email,
            $password,
            $bio,
            $image,
            $createdAt,
            $updatedAt,
        );
    }

    public function update(array $newAttributes): void
    {
        if (array_key_exists('username', $newAttributes)) {
            $this->username = $newAttributes['username'];
        }
        if (array_key_exists('email', $newAttributes)) {
            $this->email = $newAttributes['email'];
        }
        if (array_key_exists('bio', $newAttributes)) {
            $this->bio = $newAttributes['bio'];
        }
        if (array_key_exists('image', $newAttributes)) {
            $this->image = $newAttributes['image'];
        }
        if (array_key_exists('password', $newAttributes)) {
            $this->password = Hash::make($newAttributes['password']);
        }
        $this->updatedAt = CarbonImmutable::now();
    }

    public function verifyPassword(string $rawPassword): void
    {
        $verified = Hash::check($rawPassword, $this->password);

        if (!$verified) {
            throw new InvalidCredentialException("provided password is not correct");
        }
    }
}
