<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190301_170536_add_widget_ultimi_sondaggi_permission
 */
class m190301_170536_add_widget_ultimi_sondaggi_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\sondaggi\widgets\graphics\WidgetGraphicsUltimiSondaggi::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicUltimiSondaggi',
                'parent' => ['SONDAGGI_READ']
            ]
        ];
    }
}
