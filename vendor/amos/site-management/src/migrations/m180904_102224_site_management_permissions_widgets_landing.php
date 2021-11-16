<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m180904_102224_site_management_permissions_widgets_landing extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                      [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLanding::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementLanding',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR'],

        ],
        [
            'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingPubblication::className(),
            'type' => Permission::TYPE_PERMISSION,
            'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
            'description' => $prefixStr . 'WidgetIconSiteManagementLandingPubblication',
            'ruleName' => null,
            'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR'],

        ]


        ];
    }
}
