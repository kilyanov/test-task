<?php

declare(strict_types=1);

namespace app\modules\v1\records\auth;

use Yii;
use Carbon\Carbon;
use yii\base\Model;

/**
 * @OA\Schema(title="Токены пользователя")
 */
class TokenFull extends Model
{
    /**
     * @OA\Property(
     *     property="accessToken",
     *     type="string",
     *     description="access token of User",
     *     title="accessToken",
     * )
     */
    public ?string $accessToken;

    /**
     * @OA\Property(
     *     property="accessExpire",
     *     type="string",
     *     description="access expire of TIME ZONE UTC",
     *     title="accessExpire",
     * )
     */
    public ?string $accessExpire;

    /**
     * @OA\Property(
     *     property="refreshToken",
     *     type="string",
     *     description="refresh token of User",
     *     title="refreshToken",
     * )
     */
    public ?string $refreshToken;

    /**
     * @OA\Property(
     *     property="refreshExpire",
     *     type="string",
     *     description="refresh expire of TIME ZONE UTC",
     *     title="refreshExpire",
     * )
     */
    public ?string $refreshExpire;

    public function rules(): array
    {
        return [
            [
                [
                    'accessToken',
                    'accessExpire' => function($model) {
                        return Carbon::parse($model->accessExpire, Yii::$app->timeZone)->toRfc3339String();
                    },
                    'refreshToken',
                    'refreshExpire' => function($model) {
                        return Carbon::parse($model->accessExpire, Yii::$app->timeZone)->toRfc3339String();
                    },
                ],
                'safe',
            ],
        ];
    }
}