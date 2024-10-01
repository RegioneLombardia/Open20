<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m180802_121724_site_management_permissions_widgets extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                      [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainer::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementContainer',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR'],

        ],
        [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementElement::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementContainer elem',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR'],

        ]


        ];
    }
}
