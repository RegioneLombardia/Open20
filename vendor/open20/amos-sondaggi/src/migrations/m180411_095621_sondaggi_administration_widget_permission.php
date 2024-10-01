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
 * Class m180411_095621_sondaggi_administration_widget_permission
 */
class m180411_095621_sondaggi_administration_widget_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiAdministration::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSondaggiAdministration',
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ]
        ];
    }
}
