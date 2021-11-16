<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190424_154544_add_associate_user_to_event_permission
 */
class m190424_154544_add_associate_user_to_event_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ASSOCIATE_USER_TO_EVENT_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to associate user to an event',
                'parent' => ['EVENTS_ADMINISTRATOR']
            ]
        ];
    }
}
