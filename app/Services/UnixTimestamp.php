<?php

namespace App\Services;

use App\Enums\BaseConverters;
use App\Enums\TimeUnits;
use App\Services\BaseConverters\Base62Converter;
use App\Services\BaseConverters\BaseConverterContract;

class UnixTimestamp
{
    /**
     * 具体的な進数変換クラスのインスタンスを生成して返す
     *
     * @param  BaseConverters  $converters  使用したい進数変換
     * @return BaseConverterContract 具象クラスのインスタンス
     */
    private function converter(BaseConverters $converters): BaseConverterContract
    {
        return match ($converters) {
            BaseConverters::Base62 => new Base62Converter(),
        };
    }

    /**
     * 指定された時間単位のUnixタイムスタンプを返す
     *
     * @param  TimeUnits  $timeUnits  時間単位
     * @return string Unixタイムスタンプ
     */
    public function timestamp(TimeUnits $timeUnits): string
    {
        return (string) match ($timeUnits) {
            TimeUnits::MilliSecond => (float) millitime(true) * 1000,
        };
    }

    /**
     * ミリ秒単位のUnixタイムスタンプを62進数に変換する
     *
     * @return string 62進数に変換されたUnixタイムスタンプ
     */
    public function millToBase62(): string
    {
        return $this->toBase62(TimeUnits::MilliSecond);
    }

    /**
     * 指定した時間単位のUnixタイムスタンプを62進数に変換する
     *
     * @param  TimeUnits  $timeUnits  時間単位
     * @return string 62進数に変換されたUnixタイムスタンプ
     */
    public function toBase62(TimeUnits $timeUnits): string
    {
        return $this->converter(BaseConverters::Base62)->encode($this->timestamp($timeUnits));
    }

    /**
     * 62進数に変換されたUnixタイムスタンプをUnixタイムスタンプに変換する
     *
     * @param  string  $base62UnixTimestamp  62進数に変換されたUnixタイムスタンプ
     * @return string Unixタイムスタンプ
     */
    public function fromBase62(string $base62UnixTimestamp): string
    {
        return $this->converter(BaseConverters::Base62)->decode($base62UnixTimestamp);
    }
}
