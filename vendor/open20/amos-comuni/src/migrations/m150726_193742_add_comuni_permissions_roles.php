<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m150726_193742_add_comuni_permissions_roles
 */
class m150726_193742_add_comuni_permissions_roles extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions()/*,
            $this->setWidgetsPermissions()*/
        );
    }
    
    /**
     * Plugin roles.
     * @return array
     */
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_COMUNI_ISTAT',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore del Plugin dei Comuni'
            ]
        ];
    }
    
    /**
     * Model permissions
     * @return array
     */
    private function setModelPermissions()
    {
        return [
            [
                'name' => 'ISTATCOMUNI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Comuni',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ],
            [
                'name' => 'ISTATCOMUNI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Comuni',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ],
            [
                'name' => 'ISTATCOMUNI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Comuni',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ],
            [
                'name' => 'ISTATCOMUNI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Comuni',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ],
            [
                'name' => 'ISTATPROVINCE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Province',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ],
            [
                'name' => 'ISTATPROVINCE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Province',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ],
            [
                'name' => 'ISTATPROVINCE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Province',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ],
            [
                'name' => 'ISTATPROVINCE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Province',
                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
            ]
        ];
    }


//    /**
//     * Plugin widgets permissions
//     * @return array
//     */
//    private function setWidgetsPermissions()
//    {
//        return [
//            [
//                'name' => open20\amos\comuni\widgets\icons\WidgetIconAmmComuni::className(),
//                'type' => Permission::TYPE_PERMISSION,
//                'description' => 'Permesso per il widget WidgetIconAmmComuni',
//                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
//            ],
//            [
//                'name' => open20\amos\comuni\widgets\icons\WidgetIconComuni::className(),
//                'type' => Permission::TYPE_PERMISSION,
//                'description' => 'Permesso per il widget WidgetIconComuni',
//                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
//            ],
//            [
//                'name' => open20\amos\comuni\widgets\icons\WidgetIconProvince::className(),
//                'type' => Permission::TYPE_PERMISSION,
//                'description' => 'Permesso per il widget WidgetIconProvince',
//                'parent' => ['AMMINISTRATORE_COMUNI_ISTAT']
//            ]
//        ];
//    }
}
