<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m220201_151436_elasticsearch_history_permissions*/
class m220201_151436_elasticsearch_history_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'ELASTICSEARCH_MANAGE_HISTORY',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role to manage elastic search  history',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ELASTICSEARCHHISTORY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ElasticsearchHistory',
                'ruleName' => null,
                'parent' => ['ELASTICSEARCH_MANAGE_HISTORY']
            ],
            [
                'name' => 'ELASTICSEARCHHISTORY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ElasticsearchHistory',
                'ruleName' => null,
                'parent' => ['ELASTICSEARCH_MANAGE_HISTORY']
            ],
            [
                'name' => 'ELASTICSEARCHHISTORY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ElasticsearchHistory',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ELASTICSEARCHHISTORY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ElasticsearchHistory',
                'ruleName' => null,
                'parent' => ['ELASTICSEARCH_MANAGE_HISTORY']
            ],

        ];
    }
}
