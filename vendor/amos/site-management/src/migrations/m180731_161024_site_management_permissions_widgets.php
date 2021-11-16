<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m180731_161024_site_management_permissions_widgets extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                      [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementSection::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementSection',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
            'default_order' => 40

        ],
        [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementSlider::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementSection',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
            'default_order' => 40

        ]


        ];
    }
}
