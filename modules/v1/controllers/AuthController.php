<?php

declare(strict_types=1);

namespace app\modules\v1\controllers;

use app\common\BaseApiController;
use app\modules\v1\actions\auth\Confirm;
use app\modules\v1\actions\auth\Index;
use app\modules\v1\actions\auth\Logout;
use app\modules\v1\actions\auth\Refresh;
use app\modules\v1\actions\auth\Register;
use app\modules\v1\form\auth\AuthForm;
use app\modules\v1\form\auth\ConfirmForm;
use app\modules\v1\form\auth\LogoutForm;
use app\modules\v1\form\auth\RefreshForm;
use app\modules\v1\form\auth\RegisterForm;
use yii\helpers\ArrayHelper;
use yii\rest\OptionsAction;

class AuthController extends BaseApiController
{
    public string $modelClass = AuthForm::class;

    protected function verbs(): array
    {
        return [
            'index' => ['POST'],
            'refresh' => ['POST'],
            'register' => ['POST'],
            'logout' => ['POST'],
            'confirm' => ['POST'],
        ];
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['access']);
        return ArrayHelper::merge($behaviors, [
            'authenticator' => [
                'except' => ['options', 'index', 'register', 'refresh', 'logout', 'confirm',],
            ],
        ]);
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
            'confirm' => [
                'class' => Confirm::class,
                'modelClass' => ConfirmForm::class
            ],
            'refresh' => [
                'class' => Refresh::class,
                'modelClass' => RefreshForm::class
            ],
            'logout' => [
                'class' => Logout::class,
                'modelClass' => LogoutForm::class
            ],
            'register' => [
                'class' => Register::class,
                'modelClass' => RegisterForm::class
            ],
            'register-agency' => [
                'class' => RegisterAgency::class,
                'modelClass' => RegisterAgencyForm::class
            ],

        ];
    }
}
