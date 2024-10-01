<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m170410_091755_add_permission_news_validate extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NewsValidate',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a news with cwh query',
                'ruleName' => \open20\amos\core\rules\ValidatorUpdateContentRule::className(),
                'parent' => ['VALIDATORE_NEWS']
            ],
            [
                'name' => 'NEWS_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['NewsValidate'],
                    'removeParents' => ['VALIDATORE_NEWS']
                ]
            ]
        ];
    }
}
