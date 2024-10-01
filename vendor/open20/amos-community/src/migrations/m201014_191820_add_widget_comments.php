<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201014_191820_add_widget_comments
 */
class m201014_191820_add_widget_comments extends AmosMigrationPermissions
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
                'name' => \open20\amos\community\widgets\graphics\WidgetGraphicsComments::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetGraphicsComments',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER', 'COMMUNITY_CREATOR', 'COMMUNITY_READER'],
            ],
        ];
    }
}
