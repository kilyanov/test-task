<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=54321;dbname=test_yii',
    'username' => 'app',
    'password' => 'secret',
    'charset' => 'utf8',
    'tablePrefix' => 'tbl_'

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
