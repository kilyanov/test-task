<?php

declare(strict_types=1);

namespace app\modules\v1\actions\categoryUser;

use app\modules\v1\records\category\CategoryUser;
use Yii;
use yii\db\StaleObjectException;
use yii\rest\Action;

/**
 * @OA\Delete (
 *     path="/v1/category-user/{id}",
 *     tags={"Category application user"},
 *     security={{"BearerAuth": {}}},
 *     summary="Открепление категории от пользователя",
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="id записи",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *              format="uuid"
 *          )
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description="Категория от пользователя откреплена",
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
        $model = CategoryUser::find()->andWhere(['id' => $id])->one();
        if($model === null) {
            $response->setStatusCode(404);
            return 'Запись не найдена!';
        }
        if ($model->delete()) {
            return 'Запись удалена!';
        }
        $response->setStatusCode(404);
        return implode(',', $model->getFirstErrors());
    }

}
