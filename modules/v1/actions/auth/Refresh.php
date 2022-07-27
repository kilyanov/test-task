<?php

declare(strict_types=1);

namespace app\modules\v1\actions\auth;

use app\modules\v1\forms\auth\RefreshForm;
use Yii;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/auth/refresh",
 *     tags={"Auth"},
 *     summary="Обновление токена пользователя",
 *     @OA\RequestBody(
 *       required=true,
 *       @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(ref="#/components/schemas/RefreshForm")
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Объект токенов",
 *         @OA\JsonContent(ref="#/components/schemas/TokenFull")
 *     )
 * )
 */
class Refresh extends Action
{

    public function run()
    {
        $call = $this->modelClass;
        $model = new $call();
        /**
         * @var RefreshForm $model
         */
        if ($model->load(Yii::$app->request->bodyParams, '') && $model->validate()) {
            return $model->auth();
        } else {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            return implode(',', $model->getFirstErrors());
        }
    }

}
