<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m180828_103450_add_organizzazioni_roles
 */
class m180828_103450_add_organizzazioni_roles extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setWidgetsPermissions()
        );
    }

    private function setPluginRoles()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_ORGANIZZAZIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for the Organizzazioni plugin',
                'parent' => ['ADMIN'],
                'children' => [
                    'PROFILO_CREATE',
                    'PROFILO_READ',
                    'PROFILO_UPDATE',
                    'PROFILO_DELETE'
                ]
            ],
            [
                'name' => 'LETTORE_ORGANIZZAZIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'rEADER role for the Organizzazioni plugin',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'BASIC_USER'],
                'children' => [
                    'PROFILO_READ',
                    \open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo::className()
                ]
            ],
        ];
    }

    private function setWidgetsPermissions()
    {
        return [
            [
                'name' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo::className(),
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PROFILO_READ']
                ]
            ]
        ];
    }
}
