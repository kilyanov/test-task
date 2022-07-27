<?php

declare(strict_types=1);

namespace app\modules\v1\actions\categoryUser;

use app\modules\v1\records\category\CategoryApplication;
use app\modules\v1\records\category\CategoryUser;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/category-user",
 *     tags={"Category application user"},
 *     security={{"BearerAuth": {}}},
 *     summary="Закрепление категории за пользователем",
 *     @OA\RequestBody(
 *          description="Закрепление категории за пользователем",
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(ref="#/components/schemas/CategoryUserForm")
 *          )
 *      ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект категорий продукции",
 *         @OA\JsonContent(ref="#/components/schemas/CategoryUser")
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
        $form = new $call();
        if ($form->load($params, '') && $form->validate()) {
            $model = new CategoryUser();
            $model->setAttributes($form->getAttributes());
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
