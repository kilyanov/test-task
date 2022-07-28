<?php

use yii\db\Migration;


class m220727_225947_add_column_name_into_user_table extends Migration
{
    const TABLE_NAME = 'user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function safeUp()
    {
        $this->addColumn($this->table,'name',$this->string()->null()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn($this->table,'name');
    }

}
