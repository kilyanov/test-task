<?php

declare(strict_types=1);

namespace app\modules\v1\actions\application;

use app\common\rbac\CollectionRolls;
use app\modules\v1\filters\application\ListApplicationSearch;
use app\modules\v1\records\application\ListApplication;
use app\modules\v1\records\category\CategoryUser;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;

/**
 * @OA\Get(
 *     path="/v1/application",
 *     tags={"Application"},
 *     security={{"BearerAuth": {}}},
 *     summary="Список заявок",
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
 *       name="status",
 *       in="query",
 *       description="Статус заявки",
 *       required=false,
 *       @OA\Schema(
 *         type="string"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="categoryId",
 *       in="query",
 *       description="Категория заявки",
 *       required=false,
 *       @OA\Schema(
 *         type="string",
 *         format="uuid"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="email",
 *       in="query",
 *       description="Email пользователя",
 *       required=false,
 *       @OA\Schema(
 *         type="string"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="name",
 *       in="query",
 *       description="Username пользователя",
 *       required=false,
 *       @OA\Schema(
 *         type="string"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="createdAt",
 *       in="query",
 *       description="Дата создания заявки",
 *       required=false,
 *       @OA\Schema(
 *         type="string",
 *         format="date"
 *      )
 *    ),
 *     @OA\Parameter(
 *       name="updatedAt",
 *       in="query",
 *       description="Дата измененния заявки",
 *       required=false,
 *       @OA\Schema(
 *         type="string",
 *         format="date"
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
 *         description="Массив объектов заявок",
 *         @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/ListApplication")
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
        $cfg = [];
        $status = Yii::$app->request->get('status');
        if ($status !== null && !array_key_exists($status, ListApplication::getStatusList())) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            return 'Не верно указано значение статуса';
        }
        if (Yii::$app->user->can(CollectionRolls::ROLE_USER)) {
            $cfg['userId'] = Yii::$app->user->getId();
        }
        if (Yii::$app->user->can(CollectionRolls::ROLE_MODERATOR)) {
            $query = CategoryUser::find()->select('categoryId')
                ->andWhere(['userId' => Yii::$app->user->getId()]);
            if ($query->count() > 0) {
                $cfg['categoryUser'] = $query;
            }
        }
        return (new ListApplicationSearch($cfg))
            ->search($requestParams);
    }

}
