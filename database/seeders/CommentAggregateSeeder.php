<?php declare(strict_types=1);

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CommentAggregateSeeder extends Seeder
{
    public function generateComments(): array
    {
        return array_map(fn(int $index) => [
            'id'         => sprintf('comment%04d', $index),
            'body'       => fake()->paragraph(2),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
            'slug'       => sprintf('article%04d', $index),
            'author_id'  => sprintf('user%04d', $index),
        ], range(0, 14));
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('comments')->insert($this->generateComments());
    }
}
