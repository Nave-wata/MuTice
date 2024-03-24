<?php

namespace Tests\Feature\Repositories\ApiKey;

use App\Exceptions\Database\UniqueConstraintViolationException;
use App\Repositories\ApiKey\ApiKeyRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiKeyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ApiKeyRepository インスタンス
     */
    private ApiKeyRepository $apiKeyRepository;

    /**
     * テストの前処理
     *
     * @throws BindingResolutionException
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKeyRepository = app()->make(ApiKeyRepository::class);
    }

    /**
     * APIキーの新規作成テスト
     *
     * @throws UniqueConstraintViolationException
     *
     * @see ApiKeyRepository::create()
     */
    public function test_create(): void
    {
        // データが保存されることを確認
        $apiKey = $this->apiKeyRepository->create();

        $this->assertNotNull($apiKey);
        $this->assertTrue(Str::isUlid($apiKey->id));
        $this->assertTrue(Str::isUuid($apiKey->uuid));
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]{8}$/', $apiKey->suffix);

        // 引数で渡した値が保存されることを確認
        $attributes = [
            'uuid' => 'uuid',
            'suffix' => '12345678',
        ];
        $apiKey = $this->apiKeyRepository->create($attributes);

        $this->assertSame($attributes['uuid'], $apiKey->uuid);
        $this->assertSame($attributes['suffix'], $apiKey->suffix);

        // 重複する接尾語が保存されないことを確認
        $this->expectException(UniqueConstraintViolationException::class);
        $this->apiKeyRepository->create($attributes);
    }

    /**
     * 接尾語に一致するAPIキーを取得するテスト
     *
     * @throws UniqueConstraintViolationException
     *
     * @see ApiKeyRepository::getBySuffix()
     */
    public function test_get_by_suffix(): void
    {
        $createdApiKeys = array_map(fn () => $this->apiKeyRepository->create(), range(1, 10));
        $createdApiKeys = collect($createdApiKeys);
        $createdApiKey = $createdApiKeys->random();

        $apiKeys = $this->apiKeyRepository->getBySuffix($createdApiKey->suffix);
        foreach ($apiKeys as $apiKey) {
            $this->assertSame($createdApiKey->suffix, $apiKey->suffix);
        }
    }

    /**
     * APIキーが存在するか確認するテスト
     *
     * @throws UniqueConstraintViolationException
     *
     * @see ApiKeyRepository::has()
     */
    public function test_has(): void
    {
        $apiKey = $this->apiKeyRepository->create();

        $this->assertNotNull($apiKey);
        $this->assertIsString($apiKey->id);
        $this->assertIsString($apiKey->uuid);
        $this->assertIsString($apiKey->suffix);
        $this->assertTrue(Str::isUlid($apiKey->id));
        $this->assertTrue(Str::isUuid($apiKey->uuid));
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]{8}$/', $apiKey->suffix);

        $this->assertTrue($this->apiKeyRepository->has($apiKey->uuid, $apiKey->suffix));
        $this->assertFalse($this->apiKeyRepository->has('invalid_uuid', $apiKey->suffix));
    }
}
