<?php

declare(strict_types=1);

namespace app\modules\v1\actions\auth;

use app\modules\v1\form\auth\LogoutForm;
use Yii;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/auth/logout",
 *     tags={"Auth"},
 *     summary="Аннулирование токенов",
 *     @OA\RequestBody(
 *       required=true,
 *       @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(ref="#/components/schemas/LogoutForm")
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="",
 *     )
 * )
 */
class Logout extends Action
{

    public function run()
    {
        $call = $this->modelClass;
        $model = new $call();
        $model->load(Yii::$app->request->bodyParams, '');
        /**
         * @var LogoutForm $model
         */
        if ($model->logout()) {
            Yii::$app->response->setStatusCode(201);
            return 'Logout from system';
        }

        return $model;
    }

}
