<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m201222_143536_add_widget_live_sondaggi_permission  extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\sondaggi\widgets\graphics\WidgetGraphicsSondaggioLive::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicLiveSondaggi',
                'parent' => ['SONDAGGI_READ']
            ]
        ];
    }
}
