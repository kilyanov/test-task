<?php

declare(strict_types=1);

namespace app\models;

use app\common\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "{{%category_application}}".
 *
 * @property string $id
 * @property string $name Название
 * @property int|null $weight Вес
 * @property string $createdAt
 * @property string $updatedAt
 * @property string|null $deletedAt
 *
 * @property CategoryUser[] $categoryUsers
 * @property ListApplication[] $listApplications
 */
class CategoryApplication extends Model
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $addBehaviors = [
            'positionBehavior' => [
                'class' => PositionBehavior::class,
                'positionAttribute' => 'weight',
            ],
        ];

        return ArrayHelper::merge($behaviors, $addBehaviors);
    }

    public static function tableName(): string
    {
        return '{{%category_application}}';
    }

    public function rules(): array
    {
        return [
            [['name', ], 'required'],
            [['weight'], 'integer'],
            [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'weight' => 'Вес',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'deletedAt' => 'Deleted At',
        ];
    }

    public function getCategoryUsers(): ActiveQuery
    {
        return $this->hasMany(CategoryUser::class, ['categoryId' => 'id']);
    }

    public function getListApplications(): ActiveQuery
    {
        return $this->hasMany(ListApplication::class, ['categoryId' => 'id']);
    }
}
