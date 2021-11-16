<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190521_174933_add_permissions_widget_graphics_communities_recommended
 */
class m190521_174933_add_permissions_widget_graphics_communities_recommended extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return $this->setWidgetPermissions();
    }

    private function setWidgetPermissions()
    {
        return [
            [
                'name' => \open20\amos\community\widgets\graphics\WidgetGraphicsCommunitiesRecommended::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetGraphicsCommunitiesRecommended',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER', 'COMMUNITY_CREATOR', 'COMMUNITY_READER'],
            ],
        ];
    }
}
