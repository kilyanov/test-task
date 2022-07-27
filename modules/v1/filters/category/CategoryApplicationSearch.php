<?php

declare(strict_types=1);

namespace app\modules\v1\filters\category;

use app\modules\v1\records\category\CategoryApplication;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

class CategoryApplicationSearch extends CategoryApplication
{
    public function rules(): array
    {
        return [
            [
                ['id', 'name', 'weight',], 'safe'
            ]
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function search($requestParams): array|object
    {
        $this->load($requestParams, '');
        $models = CategoryApplication::find();
        if (!empty($this->id)) {
            $models->andWhere(['id' => $this->id]);
        }
        if (!empty($this->name)) {
            $models->andWhere(['ILIKE', 'name', $this->name]);
        }
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $models,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'defaultOrder' => ['weight' => SORT_ASC]
            ],
        ]);
    }
}