<?php

declare(strict_types=1);

namespace app\modules\v1\controllers;

use app\common\BaseApiController;
use app\common\rbac\CollectionRolls;
use app\modules\v1\actions\categoryUser\Create;
use app\modules\v1\actions\categoryUser\Delete;
use app\modules\v1\actions\categoryUser\Index;
use app\modules\v1\filters\category\CategoryUserSearch;
use app\modules\v1\form\category\CategoryUserForm;
use yii\filters\AccessControl;
use yii\rest\OptionsAction;

class CategoryUserController extends BaseApiController
{
    public string $modelClass = CategoryUserSearch::class;

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
                'modelClass' => CategoryUserForm::class
            ],
            'delete' => [
                'class' => Delete::class,
                'modelClass' => $this->modelClass
            ],

        ];
    }
}
