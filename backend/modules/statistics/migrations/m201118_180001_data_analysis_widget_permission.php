<?php

use backend\modules\campains\widgets\icons\WidgetCampains;
use backend\modules\statistics\widgets\icons\WidgetDataAnalysis;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m201118_180001_data_analysis_widget_permission extends AmosMigrationPermissions {

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
                'name' => WidgetDataAnalysis::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetDataAnalysis',
                'parent' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN']
            ],
        ];
    }

}
