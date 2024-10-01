<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190403_180342_add_auth_item_events */
class m211110_115224_add_auth_item_cms_events extends AmosMigrationPermissions {

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations() {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\amos\events\widgets\graphics\WidgetGraphicsCmsEvents::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicsEvents',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ]
        ];
    }

}
