<?php

use backend\modules\campains\widgets\icons\WidgetCampains;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m200820_111000_campains_widget_permission extends AmosMigrationPermissions {

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
                'name' => WidgetCampains::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetCampains',
                'parent' => ['PC_BASIC_USER']
            ],
        ];
    }

}
