<?php

use \app\common\rbac\CollectionRolls;
use yii\db\Migration;

class m210721_141938_create_roles_table extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        try {
            $roleSuperAdmin = $auth->createRole(CollectionRolls::ROLE_SUPER_ADMIN);
            $auth->add($roleSuperAdmin);
            echo 'Create role ROLE_SUPER_ADMIN' . PHP_EOL;
            $role = $auth->createRole(CollectionRolls::ROLE_ADMIN);
            $auth->add($role);
            $auth->addChild($roleSuperAdmin, $role);
            echo 'Create role ROLE_ADMIN'. PHP_EOL;
            $role = $auth->createRole(CollectionRolls::ROLE_MODERATOR);
            $auth->add($role);
            $auth->addChild($roleSuperAdmin, $role);
            echo 'Create role ROLE_MODERATOR'. PHP_EOL;
            echo 'Create role ROLE_ORG_USER'. PHP_EOL;
            $role = $auth->createRole(CollectionRolls::ROLE_USER);
            $auth->add($role);
            $auth->addChild($roleSuperAdmin, $role);
            echo 'Create role ROLE_USER'. PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
