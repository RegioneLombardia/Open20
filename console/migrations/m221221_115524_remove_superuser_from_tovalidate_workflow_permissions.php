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
class m221221_115524_remove_superuser_from_tovalidate_workflow_permissions
    extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => CollaborationProposals::STATUS_TOVALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['SUPERUSER'],
                ],
            ],
        ];
    }

}