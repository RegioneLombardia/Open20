<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\community\models\Bookmarks;
use open20\amos\core\migration\AmosMigrationWorkflow;

class m221014_104400_bookmarks_workflow_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return \yii\helpers\ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setWorkflowStatusPermissions()
        );
    }

    /**
     * Plugin roles
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [
        ];
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => Bookmarks::BOOKMARKS_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Bookmarks workflow status DRAFT permission',
                'parent' => [
                    'BOOKMARKS_ADMIN'
                ],
            ],
            [
                'name' => Bookmarks::BOOKMARKS_STATUS_TOVALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Bookmarks workflow status TOVALIDATE permission',
                'parent' => ['BOOKMARKS_ADMIN'],
            ],
            [
                'name' => Bookmarks::BOOKMARKS_STATUS_PUBLISHED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Bookmarks workflow status PUBLISHED permission',
                'parent' => ['BOOKMARKS_ADMIN'],
            ],
        ];
    }
}