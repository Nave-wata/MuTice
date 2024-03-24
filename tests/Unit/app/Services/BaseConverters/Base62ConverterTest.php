<?php

namespace Tests\Unit\app\Services\BaseConverters;

use App\Services\BaseConverters\Base62Converter;
use PHPUnit\Framework\TestCase;

class Base62ConverterTest extends TestCase
{
    /**
     * テストするクラスのインスタンス
     */
    private Base62Converter $converter;

    /**
     * 期待する62進数の文字列
     *
     * @var string
     */
    private const BASE62_CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * This method is called before the first test of this test class is run.
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->converter = new Base62Converter();
    }

    /**
     * 10進数を62進数に変換する
     *
     * @see Base62Converter::encode()
     */
    public function test_encode(): void
    {
        // 62進数の文字列が正しく設定されていること
        $this->assertSame(self::BASE62_CHARS, Base62Converter::BASE62_CHARS);

        // 10進数を62進数に変換する（数値が引数）
        $this->assertSame(self::BASE62_CHARS[0], $this->converter->encode(0));
        $this->assertSame(self::BASE62_CHARS[61], $this->converter->encode(61));
        $this->assertSame(self::BASE62_CHARS[1].self::BASE62_CHARS[0], $this->converter->encode(62));
        $this->assertSame(self::BASE62_CHARS[61].self::BASE62_CHARS[61], $this->converter->encode(3843));

        // 10進数を62進数に変換する（文字列が引数）
        $this->assertSame(self::BASE62_CHARS[0], $this->converter->encode('0'));
        $this->assertSame(self::BASE62_CHARS[61], $this->converter->encode('61'));
        $this->assertSame(self::BASE62_CHARS[1].self::BASE62_CHARS[0], $this->converter->encode('62'));
        $this->assertSame(self::BASE62_CHARS[61].self::BASE62_CHARS[61], $this->converter->encode('3843'));
    }

    /**
     * 62進数を10進数に変換する
     *
     * @see Base62Converter::decode()
     */
    public function test_decode(): void
    {
        // 62進数を10進数に変換する（数値が引数）
        $this->assertSame('0', $this->converter->decode(self::BASE62_CHARS[0]));
        $this->assertSame('61', $this->converter->decode(self::BASE62_CHARS[61]));
        $this->assertSame('62', $this->converter->decode(self::BASE62_CHARS[1].self::BASE62_CHARS[0]));
        $this->assertSame('3843', $this->converter->decode(self::BASE62_CHARS[61].self::BASE62_CHARS[61]));

        // 62進数を10進数に変換する（文字列が引数）
        $this->assertSame('0', $this->converter->decode(self::BASE62_CHARS[0]));
        $this->assertSame('61', $this->converter->decode(self::BASE62_CHARS[61]));
        $this->assertSame('62', $this->converter->decode(self::BASE62_CHARS[1].self::BASE62_CHARS[0]));
        $this->assertSame('3843', $this->converter->decode(self::BASE62_CHARS[61].self::BASE62_CHARS[61]));
    }
}
