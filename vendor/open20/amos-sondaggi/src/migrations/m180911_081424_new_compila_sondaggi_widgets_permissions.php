<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180911_081424_new_compila_sondaggi_widgets_permissions
 */
class m180911_081424_new_compila_sondaggi_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => 'COMPILATORE_SONDAGGI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRAZIONE_SONDAGGI']
                ]
            ],
            [
                'name' => \open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiOwnInterest::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCompilaSondaggiOwnInterest',
                'parent' => ['COMPILATORE_SONDAGGI']
            ],
            [
                'name' => \open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCompilaSondaggiAll',
                'parent' => ['COMPILATORE_SONDAGGI']
            ]
        ];
    }
}
