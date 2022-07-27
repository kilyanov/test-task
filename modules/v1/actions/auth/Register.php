<?php
declare(strict_types=1);

namespace app\modules\v1\actions\auth;

use Yii;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/auth/register",
 *     tags={"Auth"},
 *     summary="Регистрация пользователя",
 *     @OA\RequestBody(
 *       required=true,
 *       @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(ref="#/components/schemas/RegisterForm")
 *       )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект пользователя",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     )
 * )
 */
class Register extends Action
{

    public function run()
    {
        $call = $this->modelClass;
        $model = new $call();
        if ($model->load(Yii::$app->request->bodyParams, '') && $model->validate()) {
            if ($model->register()) {
                return 'Регистрация прошла успешно';
            }
            else {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                return implode(',', $model->getFirstErrors());
            }
        } else {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            return implode(',', $model->getFirstErrors());
        }
    }

}
