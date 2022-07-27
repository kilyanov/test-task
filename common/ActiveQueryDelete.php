<?php
declare(strict_types=1);

namespace app\common;

use yii\db\ActiveQuery;

class ActiveQueryDelete extends ActiveQuery
{
    public function __construct($modelClass, $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->notDeleted();
    }
    
    public function notDeleted($state = true)
    {
        if ($state) {
            [$tableName] = $this->getTableNameAndAlias();
            $this->andWhere([$tableName . '.deletedAt' => null]);
        }
        
        return $this;
    }
    
}
