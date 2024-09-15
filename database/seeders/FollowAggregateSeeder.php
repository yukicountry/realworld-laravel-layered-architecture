<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class FollowAggregateSeeder extends Seeder
{
    public function generateFollows(): array
    {
        return array_map(fn(int $index) => [
            'follower_id' => sprintf('user%04d', $index),
            'followee_id' => sprintf('user%04d', fake()->numberBetween(0, 14)),
        ], range(0, 14));
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('follows')->insert($this->generateFollows());
    }
}
