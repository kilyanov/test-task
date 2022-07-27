<?php

declare(strict_types=1);

namespace app\modules\v1\actions\category;

use app\modules\v1\records\category\CategoryApplication;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;

/**
 * @OA\Post(
 *     path="/v1/category",
 *     tags={"Category application"},
 *     security={{"BearerAuth": {}}},
 *     summary="Создание категории",
 *     @OA\RequestBody(
 *          description="Создание категории",
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(ref="#/components/schemas/CategoryForm")
 *          )
 *      ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект категорий продукции",
 *         @OA\JsonContent(ref="#/components/schemas/CategoryApplication")
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
            $model = new CategoryApplication();
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
