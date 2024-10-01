<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m181112_124024_site_management_permissions_widgets extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                      [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementAdministration::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementAdministration',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR'],

        ],
        [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainerFather::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementContainer',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR'],

        ] ,
            [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingFather::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementLanding',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR'],

        ],


        ];
    }
}
