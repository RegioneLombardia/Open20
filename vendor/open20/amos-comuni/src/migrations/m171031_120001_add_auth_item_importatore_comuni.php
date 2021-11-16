<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171031_120001_add_auth_item_importatore_comuni*/
class m171031_120001_add_auth_item_importatore_comuni extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\amos\comuni\widgets\icons\WidgetIconImportatoreComuni::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconImportatore',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]

        ];
    }
}