<?php declare(strict_types=1);

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class UserAggregateSeeder extends Seeder
{
    public function generateUsers(): array
    {
        return array_map(fn(int $index) => [
            'id'         => sprintf('user%04d', $index),
            'username'   => fake()->unique()->userName(),
            'email'      => fake()->unique()->safeEmail(),
            'password'   => Hash::make('secret'),
            'bio'        => fake()->optional()->sentence(),
            'image'      => fake()->randomElement(['https://picsum.photos/200', null]),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ], range(0, 14));
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert($this->generateUsers());
    }
}
