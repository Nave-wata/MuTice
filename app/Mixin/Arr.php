<?php

namespace App\Mixin;

use Closure;
use Illuminate\Support\Arr as BaseArr;

class Arr
{
    /**
     * 配列に存在しなければ要素を複数追加する
     */
    public function adds(): Closure
    {
        return static function (array $array, array $items) {
            foreach ($items as $key => $value) {
                $array = BaseArr::add($array, $key, $value);
            }

            return $array;
        };
    }
}
