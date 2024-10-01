<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\events\widgets\icons\WidgetIconSubscribedEvents;
use yii\rbac\Permission;

/**
 * Class m200609_145701_create_widget_icon_subscribed_events_permission
 */
class m200609_145701_create_widget_icon_subscribed_events_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconSubscribedEvents::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSubscribedEvents',
                'parent' => ['EVENTS_READER']
            ]
        ];
    }
}
