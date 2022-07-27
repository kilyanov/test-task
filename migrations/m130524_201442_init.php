<?php

use app\common\BaseMigration;
use app\common\Model;

class m130524_201442_init extends BaseMigration
{
    public const TABLE_NAME = 'user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    /**
     * @throws \yii\db\Exception
     */
    public function up()
    {
        $tableOptions = null;
        $this->setExtension();
        $this->createTable($this->table, [
            'id' => $this->generalId(),
            'username' => $this->string()->notNull()->unique()->comment('Логин'),
            'auth_key' => $this->string(32)->notNull()->comment('Ключ'),
            'password_hash' => $this->string()->notNull()->comment('Пароль'),
            'password_reset_token' => $this->string()->unique()->defaultValue(null)->comment('Токен для сброса пароля'),
            'email' => $this->string()->notNull()->unique()->comment('Email'),
            'verification_token' => $this->string()->null()->defaultValue(null)->comment('Токен регистрации'),
            'status' => $this->string()->notNull()->defaultValue(Model::NOT_ACTIVE),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
            'deletedAt' => $this->dateTime()->null(),
        ], $tableOptions);
        $this->setPrimary(self::TABLE_NAME);
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
