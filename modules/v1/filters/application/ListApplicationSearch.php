<?php

declare(strict_types=1);

namespace app\modules\v1\filters\application;

use app\modules\v1\records\application\ListApplication;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ListApplicationSearch extends ListApplication
{
    public ?ActiveQuery $categoryUser = null;

    public function rules(): array
    {
        return [
            [
                [
                    'id',
                    'categoryId',
                    'userId',
                    'name',
                    'email',
                    'message',
                    'answer',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'categoryUser',
                ],
                'safe'
            ]
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function search($requestParams): array|object
    {
        $this->load($requestParams, '');
        $models = ListApplication::find();
        if (!empty($this->categoryId)) {
            $models->andWhere(['categoryId' => $this->categoryId]);
        }
        if (!empty($this->categoryUser)) {
            $models->andWhere(['categoryId' => $this->categoryUser]);
        }
        if (!empty($this->id)) {
            $models->andWhere(['id' => $this->id]);
        }
        if (!empty($this->userId)) {
            $models->andWhere(['userId' => $this->userId]);
        }
        if (!empty($this->email)) {
            $models->andWhere(['ILIKE', 'email', $this->email]);
        }
        if (!empty($this->name)) {
            $models->andWhere(['ILIKE', 'name', $this->name]);
        }
        if (!empty($this->status)) {
            $models->andWhere(['status' => [$this->status]]);
        }
        if (!empty($this->createdAt)) {
            $models->andWhere([
                'BETWEEN',
                'createdAt',
                date('Y-m-d 00:00:00', strtotime($this->createdAt)),
                date('Y-m-d 23:59:59', strtotime($this->createdAt))
            ]);
        }
        if (!empty($this->updatedAt)) {
            $models->andWhere([
                'BETWEEN',
                'updatedAt',
                date('Y-m-d 00:00:00', strtotime($this->updatedAt)),
                date('Y-m-d 23:59:59', strtotime($this->updatedAt))
            ]);
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