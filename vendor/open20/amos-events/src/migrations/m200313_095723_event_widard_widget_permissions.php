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
 * Class m191003_121523_event_seats_permissions*/
class m200313_095723_event_widard_widget_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageEvents::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Manage events',
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageAllEvents::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Manage all events',
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
        ];
    }
}
