<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;


class m210514_180010_add_base_permissions_roles extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions()
        );
    }
    
    /**
     * Plugin roles.
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_USERIMPORTTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore User Import Task',
            ],
            [
                'name' => 'CREATORE_USERIMPORTTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Creatore User Import Task',
                'parent' => ['AMMINISTRATORE_USERIMPORTTASK']
            ],
            [
                'name' => 'VALIDATORE_USERIMPORTTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validatore User Import Task',
                'parent' => ['AMMINISTRATORE_USERIMPORTTASK']
            ],
            [
                'name' => 'LETTORE_USERIMPORTTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Lettore User Import Task',
                'parent' => ['AMMINISTRATORE_USERIMPORTTASK']
            ]
        ];
    }
    
    /**
     * Model permissions
     *
     * @return array
     */
    private function setModelPermissions()
    {
        return [
            [
                'name' => 'USERIMPORTTASK_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model USERIMPORTTASK',
                'parent' => ['AMMINISTRATORE_USERIMPORTTASK', 'CREATORE_USERIMPORTTASK']
            ],
            [
                'name' => 'USERIMPORTTASK_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model USERIMPORTTASK',
                'parent' => ['AMMINISTRATORE_USERIMPORTTASK', 'CREATORE_USERIMPORTTASK', 'VALIDATORE_USERIMPORTTASK', 'LETTORE_USERIMPORTTASK']
            ],
            [
                'name' => 'USERIMPORTTASK_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model USERIMPORTTASK',
                'parent' => ['AMMINISTRATORE_USERIMPORTTASK']
            ],
            [
                'name' => 'USERIMPORTTASK_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model News',
                'parent' => ['AMMINISTRATORE_USERIMPORTTASK', 'VALIDATORE_USERIMPORTTASK']
            ],
        ];
    }
    
}
