<?php declare(strict_types=1);

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class ArticleAggregateSeeder extends Seeder
{
    public function generateArticles(): array
    {
        return array_map(fn(int $index) => [
            'slug'        => sprintf('article%04d', $index),
            'title'       => fake()->sentence(5),
            'description' => fake()->sentence(10),
            'body'        => sprintf(
                <<<EOT
                    ## %s

                    %s

                    ## %s

                    ### %s

                    %s
                    EOT,
                fake()->sentence(5),
                fake()->paragraph(5),
                fake()->sentence(5),
                fake()->sentence(5),
                fake()->paragraph(5)
            ),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
            'author_id'  => sprintf('user%04d', $index),
        ], range(0, 14));
    }

    public function generateTags(): array
    {
        return array_map(fn(int $index) => [
            'slug' => sprintf('article%04d', $index),
            'name' => fake()->word(),
            'sort' => 0,
        ], range(0, 4));
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('articles')->insert($this->generateArticles());
        DB::table('tags')->insert($this->generateTags());
    }
}
