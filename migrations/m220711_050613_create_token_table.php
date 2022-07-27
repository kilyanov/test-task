<?php

use app\common\BaseMigration;

/**
 * Handles the creation of table `{{%token}}`.
 */
class m220711_050613_create_token_table extends BaseMigration
{
    const TABLE_NAME = 'token';
    private string $table = '{{%' . self::TABLE_NAME . '}}';

    private string $user = '{{%user}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->generalId(),
            'userId' => $this->generalIndex(),
            'type' => $this->string()->notNull()->comment('Тип'),
            'token' => $this->text()->notNull(),
            'expiredAt' => $this->dateTime()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);
        $this->setPrimary(self::TABLE_NAME);
        $this->createIndex(
            'idx-userId-' . self::TABLE_NAME,
            $this->table,
            'userId'
        );
        $this->addForeignKey(
            'fk-userId-' . self::TABLE_NAME,
            $this->table,
            'userId',
            $this->user,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-userId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
