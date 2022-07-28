<?php

namespace app\models;

use app\common\Model;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%list_application}}".
 *
 * @property string $id
 * @property string|null $categoryId
 * @property string|null $userId
 * @property string $name Имя
 * @property string $email Email
 * @property string $message Сообщение
 * @property string|null $answer Ответ
 * @property string $createdAt
 * @property string $updatedAt
 * @property string|null $deletedAt
 * @property string|null $status
 *
 * @property CategoryApplication $category
 * @property User $user
 */
class ListApplication extends Model
{
    public const RESOLVED = 'resolved';
    public const ACTIVE = 'active';

    public static function tableName(): string
    {
        return '{{%list_application}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'message', ], 'required'],
            [['email', 'name', 'message', 'answer',], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            [['message', 'answer',], 'string'],
            [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            ['status', 'in', 'range' => array_keys(self::getStatusList())],
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
            'name' => 'Имя',
            'email' => 'Email',
            'message' => 'Сообщение',
            'answer' => 'Ответ',
            'status' => 'Статус',
            'createdAt' => 'Созданно',
            'updatedAt' => 'Обновленно',
            'deletedAt' => 'Удалено',
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

    public static function getStatusList(): array
    {
        return [
            self::RESOLVED => 'Завершенное',
            self::ACTIVE => 'Новое',
        ];
    }

    public function getStatusValue(): string
    {
       $list = self::getStatusList();
       return $list[$this->status];
    }

}
