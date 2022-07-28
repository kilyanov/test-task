<?php

declare(strict_types=1);

namespace app\modules\v1\actions\application;

use app\models\ListApplication as ListApplicationAlias;
use app\modules\v1\records\application\ListApplication;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/application",
 *     tags={"Application"},
 *     security={{"BearerAuth": {}}},
 *     summary="Создание заявки",
 *     @OA\RequestBody(
 *          description="Создание заявки",
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(ref="#/components/schemas/ApplicationCreateForm")
 *          )
 *      ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект заявки",
 *         @OA\JsonContent(ref="#/components/schemas/ListApplication")
 *     )
 * )
 */
/**
 * @OA\Post(
 *     path="/v1/application/guest",
 *     tags={"Application"},
 *     summary="Создание заявки (не зарегистрированного пользователя)",
 *     @OA\RequestBody(
 *          description="Создание заявки",
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(ref="#/components/schemas/ApplicationCreateForm")
 *          )
 *      ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект заявки",
 *         @OA\JsonContent(ref="#/components/schemas/ListApplication")
 *     )
 * )
 */
class Create extends Action
{

    /**
     * @throws InvalidConfigException
     */
    public function run()
    {
        $response = Yii::$app->getResponse();
        $params = Yii::$app->getRequest()->getBodyParams();
        $call = $this->modelClass;
        $form = (Yii::$app->user->isGuest) ?
            new $call() :
            new $call(Yii::$app->user->identity->getAttributes(['name', 'email']));
        if ($form->load($params, '') && $form->validate()) {
            $model = new ListApplication();
            $attr = ArrayHelper::merge(
                ['userId' => Yii::$app->user->getId(), 'status' => ListApplicationAlias::ACTIVE],
                $form->getAttributes()
            );
            $model->setAttributes($attr);
            if ($model->save()) {
                return $model;
            } else {
                $response->setStatusCode(400);
                return implode(',', $model->getFirstErrors());
            }
        }

        $response->setStatusCode(400);
        return implode(',', $form->getFirstErrors());
    }

}
