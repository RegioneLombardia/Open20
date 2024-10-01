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
 * Class m210406_102752_fix_organizzazioni_widgets_permissions
 */
class m210406_102752_fix_organizzazioni_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Profilo';
        
        return [
            [
                'name' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconProfiloAll',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'LETTORE_ORGANIZZAZIONI']
            ],
            [
                'name' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'PROFILO_VALIDATOR']
                ]
            ],
            [
                'name' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloToValidate::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRATORE_ORGANIZZAZIONI']
                ]
            ]
        ];
    }
}
