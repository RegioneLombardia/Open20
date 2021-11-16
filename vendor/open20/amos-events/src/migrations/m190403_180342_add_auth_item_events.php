<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190403_180342_add_auth_item_events*/
class m190403_180342_add_auth_item_events extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
                [
                    'name' =>  \open20\amos\events\widgets\graphics\WidgetGraphicsEvents::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => $prefixStr . 'WidgetGraphicsEvents',
                    'ruleName' => null,
                    'parent' => ['ADMIN','BASIC_USER']
                ]

            ];
    }
}
