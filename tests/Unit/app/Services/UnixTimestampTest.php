<?php

namespace Tests\Unit\app\Services;

use App\Enums\TimeUnits;
use App\Services\BaseConverters\Base62Converter;
use App\Services\UnixTimestamp;
use PHPUnit\Framework\TestCase;

class UnixTimestampTest extends TestCase
{
    /**
     * UnixTimestampクラスのインスタンス
     */
    private UnixTimestamp $unixTimestamp;

    /**
     * テストケースのセットアップ
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->unixTimestamp = new UnixTimestamp();
    }

    /**
     * 指定された時間単位のUnixタイムスタンプを返すことをテスト
     *
     * @see UnixTimestamp::timestamp()
     */
    public function test_timestamp(): void
    {
        foreach (TimeUnits::cases() as $timeUnit) {
            $this->assertIsString($this->unixTimestamp->timestamp($timeUnit));
            $this->assertIsNumeric($this->unixTimestamp->timestamp($timeUnit));
            $this->assertMatchesRegularExpression('/^\d{13,}$/', $this->unixTimestamp->timestamp($timeUnit));
        }
    }

    /**
     * ミリ秒単位のUnixタイムスタンプを62進数に変換することをテスト
     *
     * @see UnixTimestamp::millToBase62()
     */
    public function test_mill_to_base62(): void
    {
        $chars = Base62Converter::BASE62_CHARS;

        $this->assertIsString($this->unixTimestamp->millToBase62());
        $this->assertMatchesRegularExpression("/^[{$chars}]+$/", $this->unixTimestamp->millToBase62());
        $this->assertSame(
            strlen($this->unixTimestamp->toBase62(TimeUnits::MilliSecond)),
            strlen($this->unixTimestamp->millToBase62())
        );
    }

    /**
     * 指定した時間単位のUnixタイムスタンプを62進数に変換することをテスト
     *
     * @see UnixTimestamp::toBase62()
     */
    public function test_to_base62(): void
    {
        $chars = Base62Converter::BASE62_CHARS;

        foreach (TimeUnits::cases() as $timeUnit) {
            $this->assertIsString($this->unixTimestamp->toBase62($timeUnit));
            $this->assertMatchesRegularExpression("/^[{$chars}]+$/", $this->unixTimestamp->toBase62($timeUnit));
            $this->assertSame(
                strlen((new Base62Converter())->encode($this->unixTimestamp->timestamp($timeUnit))),
                strlen($this->unixTimestamp->toBase62($timeUnit))
            );
        }
    }

    /**
     * 62進数に変換されたUnixタイムスタンプをUnixタイムスタンプに変換することをテスト
     *
     * @see UnixTimestamp::fromBase62()
     */
    public function test_from_base62(): void
    {
        foreach (TimeUnits::cases() as $timeUnit) {
            $base62UnixTimestamp = $this->unixTimestamp->toBase62($timeUnit);

            $this->assertIsString($this->unixTimestamp->fromBase62($base62UnixTimestamp));
            $this->assertIsNumeric($this->unixTimestamp->fromBase62($base62UnixTimestamp));
            $this->assertMatchesRegularExpression("/^\d{13,}$/", $this->unixTimestamp->fromBase62($base62UnixTimestamp));
        }
    }
}
