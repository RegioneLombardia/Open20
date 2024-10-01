<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\migrations
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\collaborations\models\CollaborationProposals;
use yii\rbac\Permission;

/**
 * 
 */
class m221215_112524_add_collaboration_proposals_workflow_permissions extends AmosMigrationPermissions
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
        return [];
    }

    /**
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => CollaborationProposals::STATUS_TOVALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status TOVALIDATE permission',
                'parent' => [
                    'VALIDATED_BASIC_USER', 'BASIC_USER',
                    'ADMIN', 'SUPERUSER'
                ],
            ],
            [
                'name' => CollaborationProposals::STATUS_REFUSED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status REFUSED permission',
                'parent' => ['ADMIN', 'SUPERUSER'],
            ],
        ];
    }

}