<?php

namespace Tests\Feature\Providers;

use Illuminate\Support\Arr;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    /**
     * アプリケーションサービスプロバイダの登録処理が正常に行われることを確認
     *
     * @see \App\Providers\AppServiceProvider::register()
     */
    public function test_register(): void
    {
        $this->assertTrue(app()->bound(\App\Repositories\ApiKey\ApiKeyRepository::class));
    }

    /**
     * アプリケーションサービスプロバイダの起動処理が正常に行われることを確認
     *
     * @see \App\Providers\AppServiceProvider::boot()
     */
    public function test_boot(): void
    {
        $methods = array_merge(
            (new \ReflectionClass(\App\Mixin\Arr::class))->getMethods(),
        );

        foreach ($methods as $method) {
            if ($method->isPublic() && ! $method->isStatic()) {
                $this->assertTrue(Arr::hasMacro($method->name));
            }
        }
    }
}
