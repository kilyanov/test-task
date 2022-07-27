<?php

use app\common\BaseMigration;

class m220727_172011_create_category_table extends BaseMigration
{
    const TABLE_NAME = 'category_application';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->generalId(),
            'name' => $this->string()->notNull()->unique()->comment('Название'),
            'weight' => $this->integer()->null()->comment('Вес'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
            'deletedAt' => $this->dateTime()->null(),
        ]);
        $this->setPrimary(self::TABLE_NAME);
    }

    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
