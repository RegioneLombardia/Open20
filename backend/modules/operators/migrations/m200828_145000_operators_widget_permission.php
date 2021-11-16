<?php

use backend\modules\operators\widgets\icons\WidgetOperators;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m200828_145000_operators_widget_permission extends AmosMigrationPermissions {

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
                'name' => WidgetOperators::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetOperators',
                'parent' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN']
            ],
        ];
    }

}
