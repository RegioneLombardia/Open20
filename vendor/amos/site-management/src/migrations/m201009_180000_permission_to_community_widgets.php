<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use amos\sitemanagement\widgets\graphics\WidgetCommunityImagesSlider;
use amos\sitemanagement\widgets\graphics\WidgetCommunityVideosSlider;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m201009_180000_permission_to_community_widgets
 */
class m201009_180000_permission_to_community_widgets extends AmosMigrationPermissions
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
                'name' => WidgetCommunityImagesSlider::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetCommunityImagesSlider',
                'parent' => ['BASIC_USER']
            ],
            [
                'name' => WidgetCommunityVideosSlider::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSMCommunitySliderElementsVideos',
                'parent' => ['BASIC_USER']
            ]
        ];
    }
}
