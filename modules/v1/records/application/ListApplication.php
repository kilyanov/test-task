<?php

declare(strict_types=1);

namespace app\modules\v1\records\application;

use app\modules\v1\records\category\CategoryApplication;
use app\modules\v1\records\user\User;
use Yii;
use app\models\ListApplication as ListApplicationModel;
use Carbon\Carbon;
use yii\db\ActiveQuery;

/**
 * @OA\Schema(title="Список заявок")
 */
class ListApplication extends ListApplicationModel
{
    /**
     * @OA\Property(property="id", type="string", format="uuid", description="ID")
     * @OA\Property(property="categoryId", type="string", format="uuid", description="ID категория")
     * @OA\Property(property="userId", type="string", format="uuid", description="ID пользователя")
     * @OA\Property(property="name", type="string", description="Имя пользователя")
     * @OA\Property(property="email", type="string", description="Email пользователя")
     * @OA\Property(property="message", type="string", description="Заявка")
     * @OA\Property(property="answer", type="string", description="Ответ на заявку")
     * @OA\Property(property="status", type="string", description="Статус заявки")
     */
    public function fields(): array
    {
        return [
            'id',
            'categoryId',
            'userId',
            'name',
            'email',
            'message',
            'answer',
            'status',
            'createdAt' => function($model) {
                return Carbon::parse($model->createdAt, Yii::$app->timeZone)->toRfc3339String();
            },
            'updatedAt' => function($model) {
                return Carbon::parse($model->updatedAt, Yii::$app->timeZone)->toRfc3339String();
            },
        ];
    }

    /**
     * @OA\Property(property="category", type="object", description="in expand: Категория",
     *     ref="#/components/schemas/CategoryApplication")
     * @OA\Property(property="user", type="object", description="in expand: Пользователь",
     *     ref="#/components/schemas/User")
     */
    public function extraFields(): array
    {
        return [
            'category',
            'user'
        ];
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CategoryApplication::class, ['id' => 'categoryId']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

}
