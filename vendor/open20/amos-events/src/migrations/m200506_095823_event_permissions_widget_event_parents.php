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
class m200506_095823_event_permissions_widget_event_parents extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventsParent::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Events parent',
                'parent' => ['SUPER_USER_EVENT', 'EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
            ],
        ];
    }
}
