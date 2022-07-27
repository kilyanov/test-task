<?php

declare(strict_types=1);

namespace app\modules\v1\actions\category;

use app\modules\v1\records\category\CategoryApplication;
use Yii;
use yii\db\StaleObjectException;
use yii\rest\Action;

/**
 * @OA\Delete (
 *     path="/v1/category/{id}",
 *     tags={"Category application"},
 *     security={{"BearerAuth": {}}},
 *     summary="Удаление категории",
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
 *     @OA\Response(
 *          response="200",
 *          description="Категория удалёна",
 *     )
 * )
 */
class Delete extends Action
{

    /**
     * @throws StaleObjectException
     */
    public function run(string $id): string
    {
        $response = Yii::$app->getResponse();
        $model = CategoryApplication::find()->andWhere(['id' => $id])->one();
        if($model === null) {
            $response->setStatusCode(404);
            return 'Категория не найдена!';
        }
        if ($model->delete() === true) {
            return 'Категория удалена!';
        }
        $response->setStatusCode(404);
        return implode(',', $model->getFirstErrors());
    }

}
