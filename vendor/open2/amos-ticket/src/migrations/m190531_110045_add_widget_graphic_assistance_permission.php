<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance;
use yii\rbac\Permission;

/**
 * Class m190531_110045_add_widget_graphic_assistance_permission
 */
class m190531_110045_add_widget_graphic_assistance_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetGraphicAssistance::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicAssistance',
                'parent' => ['OPERATORE_TICKET', 'REFERENTE_TICKET', 'AMMINISTRATORE_TICKET']
            ]
        ];
    }
}
