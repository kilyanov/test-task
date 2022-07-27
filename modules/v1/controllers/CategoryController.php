<?php

declare(strict_types=1);

namespace app\modules\v1\controllers;

use app\common\BaseApiController;
use app\common\rbac\CollectionRolls;
use app\modules\v1\actions\category\Delete;
use app\modules\v1\actions\category\Create;
use app\modules\v1\actions\category\Index;
use app\modules\v1\actions\category\Update;
use app\modules\v1\filters\category\CategoryApplicationSearch;
use app\modules\v1\form\category\CategoryForm;
use yii\filters\AccessControl;
use yii\rest\OptionsAction;

class CategoryController extends BaseApiController
{
    public string $modelClass = CategoryApplicationSearch::class;

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [CollectionRolls::ROLE_ADMIN],
                ],
            ],
        ];
        return $behaviors;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
        ];
    }

    public function actions(): array
    {
        return [
            'options' => [
                'class' => OptionsAction::class,
            ],
            'index' => [
                'class' => Index::class,
                'modelClass' => $this->modelClass
            ],
            'create' => [
                'class' => Create::class,
                'modelClass' => CategoryForm::class
            ],
            'update' => [
                'class' => Update::class,
                'modelClass' => CategoryForm::class
            ],
            'delete' => [
                'class' => Delete::class,
                'modelClass' => CategoryForm::class
            ],

        ];
    }
}
