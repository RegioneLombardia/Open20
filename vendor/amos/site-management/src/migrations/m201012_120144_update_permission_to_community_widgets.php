<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use amos\sitemanagement\widgets\graphics\WidgetCommunityImagesSlider;
use amos\sitemanagement\widgets\graphics\WidgetCommunityVideosSlider;
use amos\sitemanagement\widgets\icons\WidgetIconSMCommunitySliderElementsImages;
use amos\sitemanagement\widgets\icons\WidgetIconSMCommunitySliderElementsVideos;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180727_124144_add_news_read_rule
 */
class m201012_120144_update_permission_to_community_widgets extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetIconSMCommunitySliderElementsImages::className(),
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
                ]
            ],
            [
                'name' => WidgetIconSMCommunitySliderElementsVideos::className(),
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
                ]
            ],

            [
                'name' => WidgetCommunityImagesSlider::className(),
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER', 'SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
                ]
            ],
            [
                'name' => WidgetCommunityVideosSlider::className(),
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER', 'SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
                ]
            ],

        ];
    }
}
