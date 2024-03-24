<?php

namespace Tests\Feature\Repositories\ApiKey;

use App\Exceptions\Database\UniqueConstraintViolationException;
use App\Models\ApiKey;
use App\Repositories\ApiKey\ApiKeyEloquent;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiKeyEloquentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ApiKeyEloquent インスタンス
     */
    private ApiKeyEloquent $apiKeyEloquent;

    /**
     * テストの前処理
     *
     * @throws BindingResolutionException
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKeyEloquent = app()->make(ApiKeyEloquent::class);
    }

    /**
     * APIキーの新規作成テスト
     *
     * @throws UniqueConstraintViolationException
     *
     * @see ApiKeyEloquent::create()
     */
    public function test_create(): void
    {
        // データが保存されることを確認
        $apiKey = $this->apiKeyEloquent->create();
        $findApiKey = ApiKey::find($apiKey->id);

        $this->assertNotNull($findApiKey);
        $this->assertSame($apiKey->id, $findApiKey->id);
        $this->assertSame($apiKey->uuid, $findApiKey->uuid);
        $this->assertSame($apiKey->suffix, $findApiKey->suffix);

        // 引数で渡した値が保存されることを確認
        $attributes = [
            'uuid' => 'uuid',
            'suffix' => '12345678',
        ];
        $apiKey = $this->apiKeyEloquent->create($attributes);

        $this->assertSame($attributes['uuid'], $apiKey->uuid);
        $this->assertSame($attributes['suffix'], $apiKey->suffix);

        // 一意制約違反が発生した場合の例外が発生することを確認
        $this->expectException(UniqueConstraintViolationException::class);
        $this->apiKeyEloquent->create($attributes);
    }

    /**
     * 接尾語に一致するAPIキーを取得するテスト
     *
     * @see ApiKeyEloquent::getBySuffix()
     */
    public function test_get_by_suffix(): void
    {
        $fakeApiKeys = ApiKey::factory(10)->create();
        $fakeApiKey = $fakeApiKeys->random();

        $apiKeys = $this->apiKeyEloquent->getBySuffix($fakeApiKey->suffix);

        foreach ($apiKeys as $apiKey) {
            $this->assertSame($apiKey->suffix, $apiKey->suffix);
        }
    }

    /**
     * APIキーが存在するか確認するテスト
     *
     * @see ApiKeyEloquent::has()
     */
    public function test_has(): void
    {
        $fakeApiKeys = ApiKey::factory(10)->create();
        $fakeApiKey = $fakeApiKeys->random();

        $this->assertIsString($fakeApiKey->uuid);
        $this->assertIsString($fakeApiKey->suffix);
        $this->assertTrue($this->apiKeyEloquent->has($fakeApiKey->uuid, $fakeApiKey->suffix));
        $this->assertFalse($this->apiKeyEloquent->has($fakeApiKey->uuid.'invalid', $fakeApiKey->suffix));
        $this->assertFalse($this->apiKeyEloquent->has($fakeApiKey->uuid, $fakeApiKey->suffix.'invalid'));
        $this->assertFalse($this->apiKeyEloquent->has($fakeApiKey->uuid.'invalid', $fakeApiKey->suffix.'invalid'));
    }
}
