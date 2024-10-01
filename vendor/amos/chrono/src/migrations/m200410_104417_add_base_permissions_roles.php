<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;


class m200410_104417_add_base_permissions_roles extends AmosMigrationPermissions
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
                'name' => 'AMMINISTRATORE_CHRONOTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore Chrono Task',
            ],
            [
                'name' => 'CREATORE_CHRONOTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Creatore Chrono Task',
                'parent' => ['AMMINISTRATORE_CHRONOTASK']
            ],
            [
                'name' => 'VALIDATORE_CHRONOTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validatore User Import Task',
                'parent' => ['AMMINISTRATORE_CHRONOTASK']
            ],
            [
                'name' => 'LETTORE_CHRONOTASK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Lettore User Import Task',
                'parent' => ['AMMINISTRATORE_CHRONOTASK']
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
                'name' => 'CHRONOTASK_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CHRONOTASK',
                'parent' => ['AMMINISTRATORE_CHRONOTASK', 'CREATORE_CHRONOTASK']
            ],
            [
                'name' => 'CHRONOTASK_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CHRONOTASK',
                'parent' => ['AMMINISTRATORE_CHRONOTASK', 'CREATORE_CHRONOTASK', 'VALIDATORE_CHRONOTASK', 'LETTORE_CHRONOTASK']
            ],
            [
                'name' => 'CHRONOTASK_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CHRONOTASK',
                'parent' => ['AMMINISTRATORE_CHRONOTASK']
            ],
            [
                'name' => 'CHRONOTASK_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ChronoTask',
                'parent' => ['AMMINISTRATORE_CHRONOTASK', 'VALIDATORE_CHRONOTASK']
            ],
        ];
    }
    
}
