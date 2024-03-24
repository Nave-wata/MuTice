<?php

namespace Tests\Feature\Providers;

use Tests\TestCase;

class HelperServiceProviderTest extends TestCase
{
    /**
     * サービスプロバイダで読み込まれる関数が存在することを確認
     *
     * @see \App\Providers\HelperServiceProvider::boot()
     */
    public function test_boot(): void
    {
        $this->assertTrue(function_exists('millitime'));
    }
}
