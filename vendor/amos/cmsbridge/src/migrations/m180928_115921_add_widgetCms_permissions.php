<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m180928_115921_add_widgetCms_permissions extends AmosMigrationPermissions {

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations() {
        return $this->setWidgetsPermissions();
    }

    /**
     * Plugin widgets permissions
     *
     * @return array
     */
    private function setWidgetsPermissions() {
        return [
            [
                'name' => \amos\cmsbridge\widgets\icons\WidgetIconCmsDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconCmsDashboard',
                'parent' => ['ADMIN']
            ],
        ];
    }

}
