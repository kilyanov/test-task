<?php

namespace app\modules\swagger;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\swagger\controllers';

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
}