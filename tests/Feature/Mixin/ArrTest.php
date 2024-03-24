<?php

namespace Tests\Feature\Mixin;

use Illuminate\Support\Arr;
use Tests\TestCase;

class ArrTest extends TestCase
{
    /**
     * 元の配列に存在しなければ追加されることを確認
     */
    public function test_adds(): void
    {
        // キーの重複がない場合
        $array = [
            'key1' => 'value1',
        ];
        $items = [
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        $this->assertSame(
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ],
            Arr::adds($array, $items)
        );

        // キーの重複がある場合
        $array = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $items = [
            'key2' => 'value3',
            'key3' => 'value4',
        ];

        $this->assertSame(
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value4',
            ],
            Arr::adds($array, $items)
        );
    }
}
