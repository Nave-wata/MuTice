<?php

namespace App\Models\Traits;

use App\Exceptions\Database\Eloquent\OperationDisallowedException;

trait UpdateDisallowed
{
    /**
     * モデルが操作される前に呼び出される
     *
     * 更新処理を許可しない
     *
     * @throws OperationDisallowedException 許可されていない操作が行われた場合（更新処理）
     */
    protected static function bootUpdateDisallowed(): void
    {
        static::updating(static fn () => throw new OperationDisallowedException('Update operation is not allowed'));
    }
}
