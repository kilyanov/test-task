<?php

declare(strict_types=1);

namespace app\modules\v1\records\category;

use app\models\CategoryUser as CategoryUserModel;
use app\modules\v1\records\user\User;
use yii\db\ActiveQuery;

/**
 * @OA\Schema(title="Категории заявок")
 */
class CategoryUser extends CategoryUserModel
{
    /**
     * @OA\Property(property="id", type="string", format="uuid", description="ID")
     * @OA\Property(property="userId", type="string", format="uuid", description="Пользователь")
     * @OA\Property(property="categoryId", type="string", format="uuid", description="Категория")
     */
    public function fields(): array
    {
        return [
            'id',
            'userId',
            'categoryId'
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
