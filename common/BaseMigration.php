<?php

declare(strict_types=1);

namespace app\common;

use Yii;
use yii\db\Exception;
use yii\db\Migration;

class BaseMigration extends Migration
{
    public const TYPE_DRIVER_PGSQL = 'pgsql';
    public const TYPE_DRIVER_MYSQL = 'mysql';


    public function init(): void
    {
        parent::init();
    }

    /**
     * @throws Exception
     */
    public function setExtension(): void
    {
        if (Yii::$app->db->driverName === self::TYPE_DRIVER_PGSQL) {
            $this->db->createCommand('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";')->execute();
        }
    }

    public function generalId(): \yii\db\ColumnSchemaBuilder|string
    {
        switch (Yii::$app->db->driverName) {
            case self::TYPE_DRIVER_MYSQL:
                return $this->string()->unique();
                break;
            case self::TYPE_DRIVER_PGSQL:
                return 'uuid DEFAULT uuid_generate_v4() NOT NULL PRIMARY KEY';
                break;
        }
    }

    public function setPrimary(string $table)
    {
        if (Yii::$app->db->driverName === self::TYPE_DRIVER_MYSQL) {
            $this->addPrimaryKey('id_pk_' . $table, '{{%' . $table . '}}', ['id']);
        }
    }

    public function generalIndex(bool $stateNull = false)
    {
        switch (Yii::$app->db->driverName) {
            case self::TYPE_DRIVER_MYSQL:
                return $stateNull ? $this->string()->null() : $this->string()->notNull();
                break;
            case self::TYPE_DRIVER_PGSQL:
                return $stateNull ? 'uuid NULL' : 'uuid NOT NULL';
                break;
        }
    }


}