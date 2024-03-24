<?php

namespace Tests\Feature\Models;

use App\Exceptions\Database\Eloquent\OperationDisallowedException;
use App\Models\ApiKey;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiKeyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ApiKeyモデルの新規作成テスト
     *
     * @see ApiKey
     */
    public function test_create(): void
    {
        $apiKey = ApiKey::factory()->create();
        $findApiKey = ApiKey::find($apiKey->id);

        $this->assertNotNull($findApiKey);
        $this->assertTrue(Str::isUlid($apiKey->id));
        $this->assertTrue(Str::isUuid($apiKey->uuid));
        $this->assertMatchesRegularExpression('/[A-Za-z0-9]{8}/', $apiKey->suffix);

        $this->assertSame($apiKey->id, $findApiKey->id);
        $this->assertSame($apiKey->uuid, $findApiKey->uuid);
        $this->assertSame($apiKey->suffix, $findApiKey->suffix);
    }

    /**
     * ApiKeyモデルの読み込みテスト
     *
     * @see ApiKey
     */
    public function test_read(): void
    {
        $apiKey = ApiKey::factory()->create();
        $apiKeyRead = ApiKey::find($apiKey->id);

        $this->assertNotNull($apiKeyRead);
        $this->assertSame($apiKey->id, $apiKeyRead->id);
        $this->assertSame($apiKey->uuid, $apiKeyRead->uuid);
        $this->assertSame($apiKey->suffix, $apiKeyRead->suffix);
    }

    /**
     * ApiKeyモデルの更新テスト
     *
     * @see ApiKey
     */
    public function test_update(): void
    {
        $this->expectException(OperationDisallowedException::class);

        $apiKey = ApiKey::factory()->create();

        $apiKey->uuid = fake()->uuid;
        $apiKey->suffix = fake()->regexify('[A-Za-z0-9]{8}');

        $apiKey->save();
    }

    /**
     * ApiKeyモデルの削除テスト
     *
     * @throws BindingResolutionException
     *
     * @see ApiKey
     */
    public function test_delete(): void
    {
        $apiKey = ApiKey::factory()->create();
        $findApiKey = ApiKey::find($apiKey->id);

        $this->assertNotNull($findApiKey);
        $this->assertSame($apiKey->id, $findApiKey->id);
        $this->assertSame($apiKey->uuid, $findApiKey->uuid);
        $this->assertSame($apiKey->suffix, $findApiKey->suffix);

        $apiKey->delete();

        $this->assertDatabaseEmpty(app()->make(ApiKey::class)->getTable());
    }
}
