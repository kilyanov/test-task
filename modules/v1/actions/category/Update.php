<?php

declare(strict_types=1);

namespace app\modules\v1\actions\category;

use app\modules\v1\records\category\CategoryApplication;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;
use yii\web\UploadedFile;

/**
 * @OA\Put(
 *     path="/v1/category/{id}",
 *     tags={"Category application"},
 *     security={{"BearerAuth": {}}},
 *     summary="Редактирование категории",
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="id категории",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *              format="uuid"
 *          )
 *     ),
 *     @OA\RequestBody(
 *          description="Редактирование категории",
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
class Update extends Action
{

    /**
     * @throws InvalidConfigException
     */
    public function run(string $id)
    {
        $response = Yii::$app->getResponse();
        $params = Yii::$app->getRequest()->getBodyParams();
        $call = $this->modelClass;
        $form = new $call();
        $model = CategoryApplication::find()->andWhere(['id' => $id])->one();
        if ($model === null) {
            $response->setStatusCode(404);
            return 'Категория не найдена!';
        }
        /**
         * @var CategoryApplication $model
         */
        $form->setAttributes($model->getAttributes(['name', 'weight',]));
        if ($form->load($params, '') && $form->validate()) {
            $model->setAttributes($form->getAttributes());
            if ($model->save()) {
                return $model;
            } else {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                return implode(',', $model->getFirstErrors());
            }

        }
        $response->setStatusCode(400);
        return implode(',', $form->getFirstErrors());
    }

}
