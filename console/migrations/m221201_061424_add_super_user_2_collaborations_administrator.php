<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\collaborations\models\CollaborationProposals;
use yii\helpers\ArrayHelper;

/**
 * 
 */
class m221201_061424_add_super_user_2_collaborations_administrator
    extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
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
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => CollaborationProposals::STATUS_DRAFT,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'SUPERUSER',
                    ]
                ]
            ],
            [
                'name' => CollaborationProposals::STATUS_PUBLISHED,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'SUPERUSER',
                    ]
                ]
            ],
            [
                'name' => CollaborationProposals::STATUS_CLOSED,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'SUPERUSER',
                    ]
                ]
            ],
        ];
    }
}