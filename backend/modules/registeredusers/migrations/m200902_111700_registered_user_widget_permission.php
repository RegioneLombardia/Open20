<?php

use backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m200902_111700_registered_user_widget_permission extends AmosMigrationPermissions {

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
                'name' => WidgetRegisteredUser::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetRegisteredUser',
                'parent' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN','PC_STRUCTURE_EDITOR','PC_EDITOR']
            ],
        ];
    }

}
