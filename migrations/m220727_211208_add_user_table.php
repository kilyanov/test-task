<?php

use app\common\BaseMigration;
use app\common\Model;
use app\common\rbac\CollectionRolls;
use app\models\User;
use Carbon\Carbon;
use yii\db\Migration;
use Ramsey\Uuid\Rfc4122\UuidV4;

/**
 * Class m220727_211208_add_user_table
 */
class m220727_211208_add_user_table extends Migration
{
    const TABLE_NAME = 'user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    private array $_listUser = [
        [
            'username' => 'user1',
            'email' => 'user1@ya.ru',
            'role' => CollectionRolls::ROLE_ADMIN,
        ],
        [
            'username' => 'user2',
            'email' => 'user2@ya.ru',
            'role' => CollectionRolls::ROLE_MODERATOR,
        ],
        [
            'username' => 'user3',
            'email' => 'user3@ya.ru',
            'role' => CollectionRolls::ROLE_MODERATOR,
        ],
    ];

    /**
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        foreach ($this->_listUser as $item) {
            $role = $item['role'];
            unset($item['role']);
            $item['auth_key'] = Yii::$app->security->generateRandomString();
            $item['password_hash'] = Yii::$app->security->generatePasswordHash($item['username']);
            $item['password_reset_token'] = Yii::$app->security->generateRandomString();
            $item['verification_token'] = Yii::$app->security->generateRandomString();
            $item['status'] = Model::ACTIVE;
            $item['createdAt'] = Carbon::now()->toDateString();
            $item['updatedAt'] = Carbon::now()->toDateString();
            if (Yii::$app->db->driverName === BaseMigration::TYPE_DRIVER_MYSQL) {
                $item['id'] = UuidV4::uuid4()->toString();
            }
            $this->insert($this->table, $item);
            $user = User::findOne(['username' => $item['username']]);
            $role = $auth->getRole($role);
            $auth->assign($role, $user->id);
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function safeDown()
    {
        $command = static::getDb()->createCommand();
        $command->delete($this->table, 'id is not null');

        return $command->execute();
    }

}
