<?php
declare(strict_types=1);

namespace app\modules\v1\actions\auth;

use Yii;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/auth",
 *     tags={"Auth"},
 *     summary="Авторизация пользователя",
 *     @OA\RequestBody(
 *       required=true,
 *       @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(ref="#/components/schemas/AuthForm")
 *       )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект пользователя",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     )
 * )
 */
class Index extends Action
{

    public function run()
    {
        $call = $this->modelClass;
        $model = new $call();
        if ($model->load(Yii::$app->request->bodyParams, '') && $result = $model->login()) {
            return $result;
        } else {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            return implode(',', $model->getFirstErrors());
        }
    }

}
