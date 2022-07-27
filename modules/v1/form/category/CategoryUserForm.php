<?php

declare(strict_types=1);

namespace app\modules\v1\form\category;

use app\models\CategoryApplication;
use app\models\User;
use yii\base\Model;

/**
 * @OA\Schema(title="Форма для закрепления категории")
 */
class CategoryUserForm extends Model
{

    /**
     * @OA\Property(
     *     property="categoryId",
     *     type="string",
     *     format="uuid",
     *     description="Id категории",
     *     title="categoryId",
     * )
     */
    public ?string $categoryId = null;

    /**
     * @OA\Property(
     *     property="userId",
     *     type="string",
     *     format="uuid",
     *     description="Id пользователя",
     *     title="userId",
     * )
     */
    public ?string $userId = null;

    public function rules(): array
    {
        return [
            [['categoryId', 'userId'], 'required'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryApplication::class, 'targetAttribute' => ['categoryId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'categoryId' => 'Категория',
            'userId' => 'Пользователь',
        ];
    }

}
