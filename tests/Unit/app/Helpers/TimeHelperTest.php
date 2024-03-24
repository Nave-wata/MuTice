<?php

namespace Tests\Unit\app\Helpers;

use PHPUnit\Framework\TestCase;

class TimeHelperTest extends TestCase
{
    /**
     * This method is called before the first test of this test class is run.
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__.'/../../../../app/Helpers/TimeHelper.php';
    }

    /**
     * ミリ秒単位の UNIX タイムスタンプを取得する関数のテスト
     *
     * @see millitime()
     */
    public function test_millitime(): void
    {
        // 戻り値が期待する型であること
        $this->assertIsString(millitime());
        $this->assertIsFloat(millitime(true));

        // 戻り値が期待するフォーマットであること
        $this->assertMatchesRegularExpression('/^0.\d{3} \d{10,}$/', millitime());
        $this->assertMatchesRegularExpression('/^\d{10,}.\d{1,3}$/', (string) millitime(true));
    }
}
