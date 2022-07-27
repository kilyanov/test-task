<?php

declare(strict_types=1);

namespace app\modules\v1\filters\category;

use app\modules\v1\records\category\CategoryUser;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

class CategoryUserSearch extends CategoryUser
{
    public function rules(): array
    {
        return [
            [
                ['id', 'userId', 'categoryId',], 'safe'
            ]
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function search(array $requestParams): array|object
    {
        $models = CategoryUser::find();
        if (!empty($this->id)) {
            $models->andWhere(['{{%category_user}}.id' => $this->id]);
        }
        if (!empty($this->userId)) {
            $models->joinWith(['user']);
            $models->andWhere([
                    'or',
                    ['ILIKE', '{{%user}}.username', $this->userId],
                    ['ILIKE', '{{%user}}.email', $this->userId]
            ]);
        }
        if (!empty($this->categoryId)) {
            $models->joinWith(['category']);
            $models->andWhere(['ILIKE', '{{%category_application}}.name', $this->categoryId]);
        }
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $models,
            'pagination' => [
                'params' => $requestParams,
            ],
        ]);
    }
}