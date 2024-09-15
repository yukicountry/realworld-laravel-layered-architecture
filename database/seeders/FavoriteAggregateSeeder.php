<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class FavoriteAggregateSeeder extends Seeder
{
    public function generateFavorites(): array
    {
        return array_map(fn(int $index) => [
            'slug'    => sprintf('article%04d', fake()->numberBetween(0, 14)),
            'user_id' => sprintf('user%04d', $index),
        ], range(0, 14));
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('favorites')->insert($this->generateFavorites());
    }
}
