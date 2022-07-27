<?php

declare(strict_types=1);

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'controller' => 'v1/auth',
        'patterns' => [
            'POST' => 'index',
            'OPTIONS' => 'options',
            'POST refresh' => 'refresh',
            'OPTIONS refresh' => 'options',
            'POST confirm' => 'confirm',
            'OPTIONS confirm' => 'options',
            'POST register' => 'register',
            'OPTIONS register' => 'options',
            'POST logout' => 'logout',
            'OPTIONS logout' => 'options',
        ],
        'pluralize' => false,
    ],
    [
        'class' => UrlRule::class,
        'controller' => 'v1/profile',
        'patterns' => [
            'GET' => 'index',
            'OPTIONS' => 'options',
        ],
        'pluralize' => false,
    ],
];
