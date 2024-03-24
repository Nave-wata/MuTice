<?php

namespace App\Repositories\ApiKey;

use App\Exceptions\Database\UniqueConstraintViolationException;
use App\Models\ApiKey;
use App\Services\UnixTimestamp;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final readonly class ApiKeyEloquent implements ApiKeyRepository
{
    /**
     * APIキーモデルの操作を行うリポジトリを作成する
     *
     * @param  ApiKey  $model  DIにより提供されるAPIキーモデル
     */
    public function __construct(
        protected ApiKey $model
    ) {
        //
    }

    /**
     * 新しいAPIキーを作成する
     *
     * @param array{
     *      uuid?: string,
     *      suffix?: string,
     * } $attributes 作成するAPIキーの属性
     * @return ApiKey 作成したAPIキー
     *
     * @throws UniqueConstraintViolationException 一意制約違反が発生した場合
     * @throws Exception その他の例外が発生した場合
     */
    #[\Override]
    public function create(array $attributes = []): ApiKey
    {
        $attributes = retry(10, function () use ($attributes) {
            $attributes = Arr::adds($attributes, [
                $this->model::UUID['name'] => $this->generateNewUuid(),
                $this->model::SUFFIX['name'] => $this->generateNewSuffix(),
            ]);

            return $this->has($attributes[$this->model::UUID['name']], $attributes[$this->model::SUFFIX['name']])
                ? throw new UniqueConstraintViolationException('Failed to create a unique API key.')
                : $attributes;
        });

        return $this->model::query()->create($attributes);
    }

    /**
     * 新しいレコードに対するUUIDを生成する
     *
     * @return string UUID
     */
    private function generateNewUuid(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * 新しいレコードに対する接尾語を生成する
     *
     * @return string 接尾語
     */
    private function generateNewSuffix(): string
    {
        return Str::padLeft(
            (new UnixTimestamp())->millToBase62(),
            $this->model::SUFFIX['length'],
            '0'
        );
    }

    /**
     * 接尾語に一致するAPIキーを取得する
     *
     * @param  string  $suffix  接尾語
     * @return Collection<int, ApiKey> APIキー（複数の場合あり）
     */
    #[\Override]
    public function getBySuffix(string $suffix): Collection
    {
        return $this->model::query()
            ->where($this->model::SUFFIX['name'], $suffix)
            ->get();
    }

    /**
     * APIキーが存在するか確認する
     *
     * @param  string  $uuid  APIキー
     * @param  string  $suffix  接尾語
     * @return bool 存在する場合はtrue、存在しない場合はfalse
     */
    #[\Override]
    public function has(string $uuid, string $suffix): bool
    {
        return $this->getBySuffix($suffix)->contains($this->model::UUID['name'], $uuid);
    }
}
