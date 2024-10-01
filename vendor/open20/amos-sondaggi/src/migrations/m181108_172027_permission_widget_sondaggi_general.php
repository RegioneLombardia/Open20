<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m181108_172027_permission_widget_sondaggi_general extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIcon Programmi, progetti e risultati',
                'ruleName' => null,
                'parent' => ['ADMIN','BASIC_USER']
           ]
        ];
    }
}
