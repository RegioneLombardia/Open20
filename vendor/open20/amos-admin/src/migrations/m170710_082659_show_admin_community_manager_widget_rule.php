<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\rules\ShowCommunityManagerWidgetRule;
use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170710_082659_show_admin_community_manager_widget_rule
 */
class m170710_082659_show_admin_community_manager_widget_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconCommunityManagerUserProfiles::className(),
                'update' => true,
                'newValues' => [
                    'ruleName' => ShowCommunityManagerWidgetRule::className()
                ],
                'oldValues' => [
                    'ruleName' => null
                ]
            ]
        ];
    }
}
