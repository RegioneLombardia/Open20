<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\news\models\News;
use yii\rbac\Permission;

class m170616_131710_news_remove_update_permission_NewsValidateOnDomain extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWS_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['NewsValidateOnDomain']
                ]
            ],
        ];
    }
}