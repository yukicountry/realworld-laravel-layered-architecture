<?php declare(strict_types=1);

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
            throw new EmailDuplicatedException("Email {$email} is already taken.");
        }

        if ($checkUserExistsByUsername->handle($username)) {
            throw new UsernameDuplicatedException("Username {$username} is already taken.");
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

    /**
     * @param array{'username'?: string, 'email'?: string, 'bio'?: string|null, 'image'?: string|null, 'password'?: string} $newAttributes
     */
    public function update(array $newAttributes): void
    {
        if (array_key_exists('username', $newAttributes) && $newAttributes['username'] !== $this->username) {
            $this->username = $newAttributes['username'];
            $this->updatedAt = CarbonImmutable::now();
        }
        if (array_key_exists('email', $newAttributes) && $newAttributes['email'] !== $this->email) {
            $this->email = $newAttributes['email'];
            $this->updatedAt = CarbonImmutable::now();
        }
        if (array_key_exists('bio', $newAttributes) && $newAttributes['bio'] !== $this->bio) {
            $this->bio = $newAttributes['bio'];
            $this->updatedAt = CarbonImmutable::now();
        }
        if (array_key_exists('image', $newAttributes) && $newAttributes['image'] !== $this->image) {
            $this->image = $newAttributes['image'];
            $this->updatedAt = CarbonImmutable::now();
        }
        if (array_key_exists('password', $newAttributes)) {
            $this->password = Hash::make($newAttributes['password']);
            $this->updatedAt = CarbonImmutable::now();
        }
    }

    public function verifyPassword(string $rawPassword): void
    {
        $verified = Hash::check($rawPassword, $this->password);

        if (!$verified) {
            throw new InvalidCredentialException("Invalid password.");
        }
    }
}
