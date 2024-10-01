<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171031_160001_add_auth_item_importatore_comuni*/
class m171207_100100_add_auth_item_videoconference extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\amos\videoconference\widgets\icons\WidgetIconVideoconference::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconVideoconference',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]

        ];
    }
}