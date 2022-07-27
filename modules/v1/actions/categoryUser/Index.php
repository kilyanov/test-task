<?php

declare(strict_types=1);

namespace app\modules\v1\actions\categoryUser;

use app\modules\v1\filters\category\CategoryUserSearch;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;

/**
 * @OA\Get(
 *     path="/v1/category-user",
 *     tags={"Category application user"},
 *     security={{"BearerAuth": {}}},
 *     summary="Категории, которые определены для пользователя",
 *     @OA\Parameter(
 *       name="nameCategory",
 *       in="query",
 *       description="Название категории",
 *       required=false,
 *       @OA\Schema(
 *         type="string"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="nameOrEmailUser",
 *       in="query",
 *       description="Email или username пользователя",
 *       required=false,
 *       @OA\Schema(
 *         type="string"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="expand",
 *       in="query",
 *       description="дополнительные поля (user, category)",
 *       required=false,
 *       @OA\Schema(
 *         type="string"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="page",
 *       in="query",
 *       description="Номер страницы",
 *       required=false,
 *       @OA\Schema(
 *         type="integer"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="per-page",
 *       in="query",
 *       description="Кол-во элементов на странице",
 *       required=false,
 *       @OA\Schema(
 *         type="integer"
 *      )
 *    ),
 *     @OA\Response(
 *         response="201",
 *         description="Массив объектов категорий закрепленных за пользователем",
 *         @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/CategoryUser")
 *         )
 *     )
 * )
 */
class Index extends Action
{

    /**
     * @throws InvalidConfigException
     */
    public function run()
    {
        $attribute = [];
        if (Yii::$app->request->get('nameCategory') !== null) {
            $attribute['categoryId'] = Yii::$app->request->get('nameCategory');
        }
        if (Yii::$app->request->get('nameOrEmailUser') !== null) {
            $attribute['userId'] = Yii::$app->request->get('nameOrEmailUser');
        }
        return (new CategoryUserSearch($attribute))
            ->search(Yii::$app->request->getQueryParams());
    }

}
