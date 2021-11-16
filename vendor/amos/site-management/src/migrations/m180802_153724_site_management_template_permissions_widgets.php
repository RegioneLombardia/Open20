<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m180802_153724_site_management_template_permissions_widgets extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                      [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementTemplate::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementContainer',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR'],

        ],
        [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementFields::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementContainer elem',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR'],

        ]


        ];
    }
}
