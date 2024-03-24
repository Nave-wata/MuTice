<?php

namespace App\Models;

use App\Models\Traits\UpdateDisallowed;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property mixed $uuid 暗号化されたUUID（APIキーの主要部分）
 * @property string $suffix APIキーの接尾語
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ApiKeyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereUuid($value)
 *
 * @mixin \Eloquent
 */
class ApiKey extends Model
{
    use HasFactory,
        HasUlids,
        UpdateDisallowed;

    /**
     * APIキーの主要部分を保存するカラム
     *
     * @var array{
     *     name: string,
     *     length: int,
     * }
     */
    public const UUID = [
        'name' => 'uuid',
        'length' => 512,
    ];

    /**
     * APIキーの接尾語を保存するカラム
     *
     * @var array{
     *     name: string,
     *     length: int,
     * }
     */
    public const SUFFIX = [
        'name' => 'suffix',
        'length' => 8,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::UUID['name'],
        self::SUFFIX['name'],
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        self::UUID['name'],
        self::SUFFIX['name'],
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            self::UUID['name'] => 'encrypted',
        ];
    }
}
