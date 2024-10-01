<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m201002_180000_permission_to_community_widgets
 */
class m201002_180000_permission_to_community_widgets extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    private function setPluginRoles()
    {
        return [
           
        ];
    }

    private function setModelPermissions()
    {
        return [

        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard of community for the widget ';
        return [
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSMCommunitySliderElementsImages::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSMCommunitySliderElementsImages',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ],
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSMCommunitySliderElementsVideos::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSMCommunitySliderElementsVideos',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ]
        ];
    }
}
