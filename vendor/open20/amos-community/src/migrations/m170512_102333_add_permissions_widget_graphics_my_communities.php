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
 * Class m170512_102333_add_permissions_widget_graphics_my_communities
 */
class m170512_102333_add_permissions_widget_graphics_my_communities extends AmosMigrationPermissions
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
                'name' => \open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetGraphicsMyCommunities',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER', 'COMMUNITY_CREATOR', 'COMMUNITY_READER'],
            ],
        ];
    }
}
