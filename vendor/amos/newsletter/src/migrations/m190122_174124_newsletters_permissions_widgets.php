<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190118_130224_newsletters_permissions_widgets*/
class m190122_174124_newsletters_permissions_widgets extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => \amos\newsletter\widgets\icons\WidgetIconNewsletter::className(),
                'type' => Permission::TYPE_PERMISSION,
                'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                'description' => $prefixStr . 'WidgetIconNewsletter',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR'],
            ],



        ];
    }
}
