<?php

declare(strict_types=1);

namespace app\common;

use app\common\rbac\CollectionRolls;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class BaseApiController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['options'],
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
        return ArrayHelper::merge($behaviors, [
            /*'authenticator' => [
                'class' => JwtHttpBearerAuth::class,
                'optional' => ['options'],
            ],*/
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => ['*'],
                ],
            ],
        ]);
    }

}
