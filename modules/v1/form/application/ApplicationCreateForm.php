<?php

declare(strict_types=1);

namespace app\modules\v1\form\application;

use app\models\CategoryApplication;
use app\models\User;
use yii\base\Model;

/**
 * @OA\Schema(title="Форма для создания зявки")
 */
class ApplicationCreateForm extends Model
{

    /**
     * @OA\Property(
     *     property="categoryId",
     *     type="string",
     *     description="ID категории",
     *     title="categoryId",
     * )
     */
    public ?string $categoryId = null;

    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Имя пользователя",
     *     title="name",
     * )
     */
    public ?string $name = null;

    /**
     * @OA\Property(
     *     property="email",
     *     type="string",
     *     description="Email пользователя",
     *     title="email",
     * )
     */
    public ?string $email = null;

    /**
     * @OA\Property(
     *     property="message",
     *     type="string",
     *     description="Сообзение пользователя",
     *     title="message",
     * )
     */
    public ?string $message = null;


    public function rules(): array
    {
        return [
            [['categoryId', 'name', 'email', 'message',], 'required'],
            [['name', 'email', 'message', ], 'string'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryApplication::class, 'targetAttribute' => ['categoryId' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'categoryId' => 'Категория',
            'name' => 'Имя',
            'email' => 'Email',
            'message' => 'Сообщение',
        ];
    }
}
