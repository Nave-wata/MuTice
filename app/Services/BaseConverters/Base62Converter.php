<?php

namespace App\Services\BaseConverters;

use NaveWata\BaseConversion\BaseConversion;

final readonly class Base62Converter implements BaseConverterContract
{
    /**
     * 62進数の文字列
     * 0 - 9, A - Z, a - z
     *
     * @var string
     */
    public const BASE62_CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * 62進数変換クラスのインスタンスを生成する
     */
    public function __construct(
        private BaseConversion $converter = new BaseConversion(self::BASE62_CHARS),
    ) {
        //
    }

    /**
     * 10進数を62進数に変換する
     *
     * @param  int|string  $decimal  10進数
     * @return string 62進数
     */
    #[\Override]
    public function encode(int|string $decimal): string
    {
        return $this->converter->decimalToCustom((string) $decimal);
    }

    /**
     * 62進数を10進数に変換する
     *
     * @param  string  $custom  62進数
     * @return string 10進数
     */
    #[\Override]
    public function decode(string $custom): string
    {
        return $this->converter->customToDecimal($custom);
    }
}
