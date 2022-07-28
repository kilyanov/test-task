<?php

declare(strict_types=1);

namespace app\modules\v1\actions\application;

use app\modules\v1\records\application\ListApplication;
use app\modules\v1\records\category\CategoryApplication;
use app\modules\v1\records\category\CategoryUser;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\rest\Action;

/**
 * @OA\Put(
 *     path="/v1/application/{id}",
 *     tags={"Application"},
 *     security={{"BearerAuth": {}}},
 *     summary="Ответ на заявку",
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="id заявки",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *              format="uuid"
 *          )
 *     ),
 *     @OA\RequestBody(
 *          description="Ответ на заявки",
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(ref="#/components/schemas/ApplicationUpdateForm")
 *          )
 *      ),
 *     @OA\Response(
 *         response="201",
 *         description="Объект pfzdrb",
 *         @OA\JsonContent(ref="#/components/schemas/ListApplication")
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
        $model = ListApplication::find()->andWhere(['id' => $id])->one();
        if ($model === null) {
            $response->setStatusCode(404);
            return 'Заявка не найдена!';
        }
        /**
         * @var ListApplication $model
         */
        $query = CategoryUser::find()
            ->andWhere([
                'userId' => Yii::$app->user->getId(),
                'categoryId' => $model->categoryId
            ]);
        if ($query->count() > 0 && !$query->exists()) {
            $response->setStatusCode(404);
            return 'Вы не можете отвечать на заявки в категории - ' . $model->category->name;
        }
        $form = new $call($model);
        /**
         * @var CategoryApplication $model
         */
        if ($form->load($params, '') && $form->validate()) {
            $attr = ArrayHelper::merge(['status' => ListApplication::RESOLVED], $form->getAttributes());
            $model->setAttributes($attr);
            if ($model->save()) {
                $form->sendEmail();
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
