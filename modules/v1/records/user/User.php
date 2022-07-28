<?php

declare(strict_types=1);

namespace app\modules\v1\records\user;

use Yii;
use app\models\User as UserModel;
use Carbon\Carbon;

/**
 * @OA\Schema(title="Пользователь")
 */
class User extends UserModel
{
    /**
     * @OA\Property(property="id", type="string", format="uuid", description="ID пользователя")
     * @OA\Property(property="username", type="string", description="Логин пользователя")
     * @OA\Property(property="name", type="string", description="Имя пользователя")
     * @OA\Property(property="email", type="string", description="Email пользователя")
     * @OA\Property(property="status", type="string", description="Статус пользователя")
     * @OA\Property(property="createdAt", type="string", description="Дата создания")
     * @OA\Property(property="updatedAt", type="string", description="Дата обновления")
     */
    public function fields(): array
    {
        return [
            'id',
            'name',
            'username',
            'email',
            'status',
            'createdAt' => function($model) {
                return Carbon::parse($model->createdAt, Yii::$app->timeZone)->toRfc3339String();
            },
            'updatedAt' => function($model) {
                return Carbon::parse($model->updatedAt, Yii::$app->timeZone)->toRfc3339String();
            },
        ];
    }

}
