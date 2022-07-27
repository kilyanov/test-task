<?php

declare(strict_types=1);

namespace app\modules\v1\actions\auth;

use app\modules\v1\form\auth\ConfirmForm;
use Yii;
use yii\base\Exception;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/auth/confirm",
 *     tags={"Auth"},
 *     summary="Подтверждение email пользователя",
 *     @OA\RequestBody(
 *       required=true,
 *       @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(ref="#/components/schemas/ConfirmForm")
 *       )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект пользователя",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     )
 * )
 */
class Confirm extends Action
{

    /**
     * @throws Exception
     */
    public function run()
    {
        $call = $this->modelClass;
        $model = new $call();
        if ($model->load(Yii::$app->request->bodyParams, '') && $model->validate()) {
            /**
             * @var ConfirmForm $model
             */
            if (!$model->verification()) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                return implode(',', $model->getFirstErrors());
            }
            else return 'Подтверждение учетной записи прошло успешно.';
        } else {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            return implode(',', $model->getFirstErrors());
        }
    }

}
