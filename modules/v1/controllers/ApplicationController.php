<?php

declare(strict_types=1);

namespace app\modules\v1\controllers;

use app\common\BaseApiController;
use app\common\rbac\CollectionRolls;
use app\modules\v1\actions\application\Create;
use app\modules\v1\actions\application\Index;
use app\modules\v1\actions\application\Update;
use app\modules\v1\filters\application\ListApplicationSearch;
use app\modules\v1\form\application\ApplicationCreateForm;
use app\modules\v1\form\application\ApplicationUpdateForm;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\OptionsAction;

class ApplicationController extends BaseApiController
{
    public string $modelClass = ListApplicationSearch::class;

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['options', 'create',],
            'authMethods' => [
                [
                    'class' => HttpBearerAuth::class,
                ],
                [
                    'class' => HttpBearerAuth::class,
                    'pattern' => '/^(.*?)$/'
                ],
                QueryParamAuth::class
            ]
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => [CollectionRolls::ROLE_USER, CollectionRolls::ROLE_MODERATOR, CollectionRolls::ROLE_ADMIN],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => [CollectionRolls::ROLE_USER, '?'],
                ],
                [
                    'actions' => ['update',],
                    'allow' => true,
                    'roles' => [CollectionRolls::ROLE_MODERATOR],
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
            'update' => ['PUT'],
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
                'modelClass' => ApplicationCreateForm::class
            ],
            'update' => [
                'class' => Update::class,
                'modelClass' => ApplicationUpdateForm::class
            ],
        ];
    }
}
