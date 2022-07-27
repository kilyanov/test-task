<?php

declare(strict_types=1);

namespace app\modules\v1\actions\profile;

use app\models\User as UserAlias;
use app\modules\v1\records\user\User;
use Yii;
use yii\rest\Action;

/**
 * @OA\Get(
 *     path="/v1/profile",
 *     security={{"BearerAuth": {}}},
 *     tags={"Profile"},
 *     summary="Профиль пользователя",
 *     @OA\Response(
 *         response="201",
 *         description="Объект пользователя",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     )
 * )
 */
class Index extends Action
{

    public function run(): ?User
    {
        return User::findOne([
            'id' => Yii::$app->user->getId(),
            'status' => UserAlias::STATUS_ACTIVE
        ]);
    }

}
