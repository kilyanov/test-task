<?php

declare(strict_types=1);

namespace app\modules\v1\form\category;

use yii\base\Model;

/**
 * @OA\Schema(title="Форма для создания категории")
 */
class CategoryForm extends Model
{

    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Название категории",
     *     title="name",
     * )
     */
    public ?string $name = null;


    /**
     * @OA\Property(
     *     property="weight",
     *     type="integer",
     *     description="Вес категории (не обязательный параметр)",
     *     title="weight",
     * )
     */
    public ?int $weight = null;


    public function rules(): array
    {
        return [
            [['name', ], 'required'],
            [['name', ], 'string'],
            [['weight', ], 'integer'],
        ];
    }

}
