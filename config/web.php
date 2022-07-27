<?php

use yii\rbac\DbManager;
use bizley\jwt\Jwt;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$urlManagerV1Rules = require(__DIR__ . '/../modules/v1/urlManagerRules.php');
$urlManagerSwaggerRules = require(__DIR__ . '/../modules/swagger/urlManagerRules.php');

$config = [
    'id' => 'app-test',
    'name' => 'TEST-yii',
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => app\modules\v1\Module::class,
        ],
        'swagger' => [
            'basePath' => '@app/modules/swagger',
            'class' => app\modules\swagger\Module::class
        ],
    ],
    'components' => [
        'response' => [
            'format' => 'json',
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ]
            ]
        ],
        'request' => [
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
                'multipart/form-data' => yii\web\MultipartFormDataParser::class
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'htmlLayout' => 'layouts/mail-html',
            'textLayout' => 'layouts/mail-text',
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['info@onyxgrp.ru' => 'onyxgrp.ru'],
            ],
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.hostland.ru',
                'username' => 'noreplay@onyxgrp.ru',
                'password' => 'rbkmzyjdFC1900',
                'port' => '25',
                'encryption' => '',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => array_merge(
                $urlManagerSwaggerRules,
                $urlManagerV1Rules
            ),
        ],
        'authManager' => [
            'class' => DbManager::class,
        ],
        'formatter' => [
            'locale' => 'ru-RU',
            'defaultTimeZone' => 'Europe/Moscow',
        ],
        'jwt' => [
            'class' => Jwt::class,
            'signer' => Jwt::RS256,
            'signingKey' => '@app/runtime/jwtRS256.key',
            'verifyingKey' => '@app/runtime/jwtRS256.key.pub',
            'validationConstraints' => [
                [
                    function() {
                        return Yii::createObject(LooseValidAt::class, [
                            'clock' => SystemClock::fromUTC(),
                        ]);
                    },
                ],
                [
                    function() {
                        $builder = Yii::$app->jwt->getConfiguration();
                        return Yii::createObject(SignedWith::class, [
                            'signer' => $builder->signer(),
                            'key' => $builder->verificationKey(),
                        ]);
                    },
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
