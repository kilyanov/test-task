<?php

declare(strict_types=1);

namespace app\modules\v1\controllers;

use app\common\BaseApiController;
use app\modules\v1\actions\profile\Index;
use app\modules\v1\records\user\User;
use yii\rest\OptionsAction;

class ProfileController extends BaseApiController
{
    public string $modelClass = User::class;

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
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
        ];
    }
}
