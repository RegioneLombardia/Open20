<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190118_130224_newsletters_permissions_widgets*/
class m190118_130224_newsletters_permissions_widgets extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => \amos\newsletter\widgets\icons\WidgetIconNewsletterDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                'description' => $prefixStr . 'WidgetIconNewsletterDashboard',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR'],
            ],
            [
                'name' => \amos\newsletter\widgets\icons\WidgetIconServiceMail::className(),
                'type' => Permission::TYPE_PERMISSION,
                'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                'description' => $prefixStr . 'WidgetIconServiceMail',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR'],
            ],
            [
                'name' => \amos\newsletter\widgets\icons\WidgetIconConfigurationFields::className(),
                'type' => Permission::TYPE_PERMISSION,
                'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                'description' => $prefixStr . 'WidgetIconConfigurationFields',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR'],
            ],


        ];
    }
}
