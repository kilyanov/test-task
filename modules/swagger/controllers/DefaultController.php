<?php

namespace app\modules\swagger\controllers;

use Yii;
use yii\web\Controller;
use function OpenApi\scan;

class DefaultController extends Controller
{

    public function actionSwagger()
    {
        $token = 'test';

        Yii::$app->response->headers->set('content-type', 'application/json');
        Yii::$app->response->headers->set('Authorization', "Bearer $token");
        Yii::$app->response->format = 'json';

        $openapi = scan(Yii::getAlias('@app/modules/v1'));

        return json_decode($openapi->toJson(  JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}