<?php

declare(strict_types=1);

namespace App\Queries\Services;

use App\Queries\Models\Profile;
use App\Queries\Models\SingleArticle;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @coversDefaultClass App\Queries\Services\ArticleQueryService;
 */
final class ArticleQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshTestDatabase();
    }

    public static function providerTestGetSingleArticle(): array
    {
        $testData = [
            'users' => [
                [
                    'id'          => 'user1',
                    'username'    => 'sleepyman',
                    'email'       => 'sleepyman@example.com',
                    'password'    => 'secret',
                    'bio'         => "I'm sleepy",
                    'image'       => 'https://example.com/sleepyman.png',
                    'created_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                    'updated_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                ],
                [
                    'id'          => 'user2',
                    'username'    => 'hungryman',
                    'email'       => 'hungryman@example.com',
                    'password'    => 'secret',
                    'bio'         => null,
                    'image'       => null,
                    'created_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                    'updated_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                ],
            ],
            'articles' => [
                [
                    'slug'        => 'article1',
                    'title'       => 'title1',
                    'description' => 'description1',
                    'body'        => 'body1',
                    'created_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                    'updated_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                    'author_id'   => 'user1',
                ],
                [
                    'slug'        => 'article2',
                    'title'       => 'title2',
                    'description' => 'description2',
                    'body'        => 'body2',
                    'created_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                    'updated_at'  => CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
                    'author_id'   => 'user2',
                ],
            ],
            'tags' => [
                [
                    'slug' => 'article1',
                    'name' => 'PHP',
                    'sort' => 0,
                ],
                [
                    'slug' => 'article1',
                    'name' => 'Laravel',
                    'sort' => 1,
                ],
                [
                    'slug' => 'article2',
                    'name' => 'TypeScript',
                    'sort' => 0,
                ],
            ],
            'favorites' => [
                [
                    'slug'    => 'article1',
                    'user_id' => 'user1',
                ],
            ],
        ];

        $article1 = new SingleArticle(
            slug: 'article1',
            title: 'title1',
            description: 'description1',
            body: 'body1',
            tagList: ['PHP', 'Laravel'],
            createdAt: CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
            updatedAt: CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
            favorited: true,
            favoritesCount: 1,
            author: new Profile(
                username: 'sleepyman',
                bio: "I'm sleepy",
                image: 'https://example.com/sleepyman.png',
                following: false,
            ),
        );

        $article2 = new SingleArticle(
            slug: 'article2',
            title: 'title2',
            description: 'description2',
            body: 'body2',
            tagList: ['TypeScript'],
            createdAt: CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
            updatedAt: CarbonImmutable::create(2024, 9, 15, 21, 0, 0, 'UTC'),
            favorited: false,
            favoritesCount: 0,
            author: new Profile(
                username: 'hungryman',
                bio: null,
                image: null,
                following: false,
            ),
        );

        return [
            'success' => [
                'testData'      => $testData,
                'slug'          => 'article1',
                'currentUserId' => 'user1',
                'expected'      => $article1,
            ],
            'success: article is not favorited' => [
                'testData'      => $testData,
                'slug'          => 'article2',
                'currentUserId' => 'user1',
                'expected'      => $article2,
            ],
            'success: current user id is null' => [
                'testData'      => $testData,
                'slug'          => 'article2',
                'currentUserId' => null,
                'expected'      => $article2,
            ],
            'success: article not found' => [
                'testData'      => $testData,
                'slug'          => 'article9999',
                'currentUserId' => 'user1',
                'expected'      => null,
            ],
        ];
    }

    /**
     * @covers ::getSingleArticle
     * @dataProvider providerTestGetSingleArticle
     */
    public function testGetSingleArticle(
        array $testData,
        string $slug,
        ?string $currentUserId,
        ?SingleArticle $expected,
    ): void {
        // setup test data
        DB::table('users')->insert($testData['users']);
        DB::table('articles')->insert($testData['articles']);
        DB::table('tags')->insert($testData['tags']);
        DB::table('favorites')->insert($testData['favorites']);

        $queryService = new ArticleQueryService(new ProfileQueryService());

        $actual = $queryService->getSingleArticle($slug, $currentUserId);

        $this->assertEquals($expected, $actual);
    }
}
