<?php

namespace App\Repositories\ApiKey;

use App\Exceptions\Database\UniqueConstraintViolationException;
use App\Models\ApiKey;
use Illuminate\Database\Eloquent\Collection;

interface ApiKeyRepository
{
    /**
     * 新しいAPIキーを作成する
     *
     * @param array{
     *      uuid?: string,
     *      suffix?: string,
     * } $attributes 作成するAPIキーの属性
     * @return ApiKey 作成したAPIキー
     *
     * @throws UniqueConstraintViolationException
     */
    public function create(array $attributes = []): ApiKey;

    /**
     * 接尾語に一致するAPIキーを取得する
     *
     * @param  string  $suffix  接尾語
     * @return Collection<int, ApiKey> APIキー（複数の場合あり）
     */
    public function getBySuffix(string $suffix): Collection;

    /**
     * APIキーが存在するか確認する
     *
     * @param  string  $uuid  APIキー
     * @param  string  $suffix  接尾語
     * @return bool 存在する場合はtrue、存在しない場合はfalse
     */
    public function has(string $uuid, string $suffix): bool;
}
