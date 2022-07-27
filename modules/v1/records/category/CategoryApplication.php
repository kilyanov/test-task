<?php

declare(strict_types=1);

namespace app\modules\v1\records\category;

use app\models\CategoryApplication as CategoryApplicationModel;

/**
 * @OA\Schema(title="Категории заявок")
 */
class CategoryApplication extends CategoryApplicationModel
{
    /**
     * @OA\Property(property="id", type="string", format="uuid", description="ID")
     * @OA\Property(property="name", type="string", description="Название")
     * @OA\Property(property="weight", type="integer", description="Вес")
     */
    public function fields(): array
    {
        return [
            'id',
            'name',
            'weight'
        ];
    }
}