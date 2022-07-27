<?php

use app\common\BaseMigration;

class m220727_172013_create_list_application_table extends BaseMigration
{
    const TABLE_NAME = 'list_application';

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
            'categoryId' => $this->generalIndex(true),
            'userId' => $this->generalIndex(true),
            'name' => $this->string()->notNull()->comment('Имя'),
            'email' => $this->string()->notNull()->comment('Email'),
            'message' => $this->text()->notNull()->comment('Сообщение'),
            'answer' => $this->text()->null()->comment('Ответ'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
            'deletedAt' => $this->dateTime()->null(),
        ]);
        if (Yii::$app->db->driverName === BaseMigration::TYPE_DRIVER_MYSQL) {
            $this->addColumn($this->table,'status',"enum(\"active\",\"inactive\") NOT NULL DEFAULT \"active\"");
        }
        if (Yii::$app->db->driverName === BaseMigration::TYPE_DRIVER_PGSQL) {
            $query = Yii::$app->db->createCommand("select exists (select 1 from pg_type where typname = 'status_enum')");
            if ($query->queryScalar() === false){
                $this->execute("CREATE TYPE status_enum AS ENUM('active', 'resolved')");
            }
            $this->addColumn($this->table,'status','status_enum');
        }
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
