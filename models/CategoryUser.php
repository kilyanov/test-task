<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%category_user}}".
 *
 * @property string $id
 * @property string $categoryId
 * @property string|null $userId
 *
 * @property CategoryApplication $category
 * @property User $user
 */
class CategoryUser extends \yii\db\ActiveRecord
{

    public static function tableName(): string
    {
        return '{{%category_user}}';
    }

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
            'id' => 'ID',
            'categoryId' => 'Категория',
            'userId' => 'Пользователь',
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
