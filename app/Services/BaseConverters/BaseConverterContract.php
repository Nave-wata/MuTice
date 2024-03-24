<?php

namespace App\Services\BaseConverters;

interface BaseConverterContract
{
    /**
     * 10進数をカスタム進数に変換する
     *
     * @param  int|string  $decimal  10進数
     */
    public function encode(int|string $decimal): string;

    /**
     * カスタム進数を10進数に変換する
     *
     * @param  string  $custom  カスタム進数
     * @return string 10進数
     */
    public function decode(string $custom): string;
}
