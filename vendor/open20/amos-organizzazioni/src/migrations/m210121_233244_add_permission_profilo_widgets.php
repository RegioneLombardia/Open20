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
use yii\rbac\Permission;

/**
 * Class m210121_233244_add_permission_profilo_widgets
 */
class m210121_233244_add_permission_profilo_widgets extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the widget ';
        
        return [
            [
                'name' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloToValidate::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconProfiloToValidate',
                'parent' => ['PROFILO_VALIDATOR']
            ],
            [
                'name' => 'open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloDashboard',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconProfiloDashboard',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'PROFILO_VALIDATOR', 'LETTORE_ORGANIZZAZIONI']
            ]
        ];
    }
}
