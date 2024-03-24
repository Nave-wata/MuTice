<?php

if (! function_exists('millitime')) {
    /**
     * ミリ秒単位の UNIX タイムスタンプを取得する
     *
     * @return string|float ミリ秒単位の UNIX タイムスタンプ
     */
    function millitime(bool $as_float = false): string|float
    {
        [$micro, $sec] = explode(' ', microtime());

        $micro = floor((float) $micro * 1000);

        if ($as_float) {
            return (float) $sec + $micro / 1000;
        }

        return '0.'.str_pad((string) $micro, 3, '0').' '.$sec;
    }
}
