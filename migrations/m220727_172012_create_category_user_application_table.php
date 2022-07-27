<?php

use app\common\BaseMigration;

class m220727_172012_create_category_user_application_table extends BaseMigration
{
    const TABLE_NAME = 'category_user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    private string $user = '{{%user}}';
    private string $category = '{{%category_application}}';

    /**
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->generalId(),
            'categoryId' => $this->generalIndex(),
            'userId' => $this->generalIndex(true),
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
        $this->createIndex(
            'idx-categoryId-' . self::TABLE_NAME,
            $this->table,
            'categoryId'
        );
        $this->addForeignKey(
            'fk-categoryId-' . self::TABLE_NAME,
            $this->table,
            'categoryId',
            $this->category,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-categoryId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-userId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
