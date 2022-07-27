<?php

namespace app\modules\v1;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * @OA\OpenApi(
 * 		@OA\Info(
 *          title="TestApi",
 *          description="REST API",
 *          version="1.0",
 *          @OA\Contact(email="lsd-7d@yandex.ru"),
 *      ),
 * ),
 * @OA\Schema(
 *      schema="ErrorModel",
 *      title="Сообщение об ошибке",
 *      required={"code", "message"},
 *      @OA\Property(
 *          property="code",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="message",
 *          type="string"
 *      )
 *  ),
 * @OA\SecurityScheme(
 *      securityScheme="BearerAuth",
 *      type="apiKey",
 *      in="header",
 *      name="Authorization",
 *  )
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\v1\controllers';

    public function init()
    {
        parent::init();
        Yii::$app->response->on('beforeSend', function ($event) {
            /** @var Response $response */
            $response = $event->sender;
            if ($response->statusCode < 400) {
                $response->data = [
                    'success' => true,
                    'status' => $response->statusCode,
                    'data' => $response->data,
                ];
            } else {
                ArrayHelper::remove($response->data, 'status');
                ArrayHelper::remove($response->data, 'type');
                $response->data = [
                    'success' => false,
                    'status' => $response->statusCode,
                    'data' => $response->data,
                ];
            }
        });
    }
}
