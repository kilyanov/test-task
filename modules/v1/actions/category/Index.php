<?php

declare(strict_types=1);

namespace app\modules\v1\actions\category;

use app\modules\v1\filters\category\CategoryApplicationSearch;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;

/**
 * @OA\Get(
 *     path="/v1/category",
 *     tags={"Category application"},
 *     security={{"BearerAuth": {}}},
 *     summary="Категории заявок",
 *     @OA\Parameter(
 *       name="id",
 *       in="query",
 *       description="id",
 *       required=false,
 *       @OA\Schema(
 *         type="uuid"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="name",
 *       in="query",
 *       description="Название категории",
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
 *         description="Массив объектов категорий",
 *         @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/CategoryApplication")
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
        $requestParams = Yii::$app->request->getQueryParams();
        return (new CategoryApplicationSearch())
            ->search($requestParams);
    }

}
