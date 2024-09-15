<?php declare(strict_types=1);

namespace Tests\Feature;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;

final class RegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    public const ENDPOINT = '/api/users';

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshTestDatabase();
    }

    public function testSuccess(): void
    {
        $requestBody = [
            'user' => [
                'username' => 'sleepyman',
                'email'    => 'user1@example.com',
                'password' => 'secret',
            ],
        ];

        $response = $this->postJson(self::ENDPOINT, $requestBody);
        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json
                    ->where('user.username', 'sleepyman')
                    ->where('user.email', 'user1@example.com')
                    ->whereType('user.token', 'string')
                    ->where('user.bio', null)
                    ->where('user.image', null)
            );
    }

    public function testValidationError(): void
    {
        $requestBody = [
            'user' => [
                'username' => '',
                'email'    => '',
                'password' => '',
            ],
        ];
        $response = $this->postJson(self::ENDPOINT, $requestBody);

        $response->assertUnprocessable();
    }

    public function testEmailDuplicationError(): void
    {
        $testData = [
            'id'         => 'user1',
            'username'   => 'sleepyman',
            'email'      => 'user1@example.com',
            'password'   => 'secret',
            'bio'        => null,
            'image'      => null,
            'created_at' => CarbonImmutable::create(2024, 9, 16, 19, 0, 0, 'UTC'),
            'updated_at' => CarbonImmutable::create(2024, 9, 16, 19, 0, 0, 'UTC'),
        ];
        DB::table('users')->insert($testData);

        $requestBody = [
            'user' => [
                'username' => 'hungryman',
                'email'    => 'user1@example.com',
                'password' => 'secret',
            ],
        ];

        $response = $this->postJson(self::ENDPOINT, $requestBody);

        $response->assertUnprocessable();
    }

    public function testUsernameDuplicationError(): void
    {
        $testData = [
            'id'         => 'user1',
            'username'   => 'sleepyman',
            'email'      => 'user1@example.com',
            'password'   => 'secret',
            'bio'        => null,
            'image'      => null,
            'created_at' => CarbonImmutable::create(2024, 9, 16, 19, 0, 0, 'UTC'),
            'updated_at' => CarbonImmutable::create(2024, 9, 16, 19, 0, 0, 'UTC'),
        ];
        DB::table('users')->insert($testData);

        $requestBody = [
            'user' => [
                'username' => 'sleepyman',
                'email'    => 'user2@example.com',
                'password' => 'secret',
            ],
        ];

        $response = $this->postJson(self::ENDPOINT, $requestBody);

        $response->assertUnprocessable();
    }
}
